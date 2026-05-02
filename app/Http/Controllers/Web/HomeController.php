<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $listCategory = Category::all();
        $listProduct = Product::with(['Category'])->where('parent_id', '=', null)->where('status', '=', 1)->paginate(8);
        $listBanner = Banner::where('status', 1)->get();
        return view('web.home.index', compact('listCategory', 'listProduct', 'listBanner'));
    }

    public function search(Request $request)
    {
        $search = $request->get('search');

        // Track search activity
        if ($search) {
            \App\Services\ActivityTracker::trackSearch($search);
        }

        $listCategory = Category::all();

        $listProduct = Product::where('name', 'like', '%' . $search . '%');

        $listProductIdsByAttrSearch = getListProductIdsByAttrSearch();

        if (is_array($listProductIdsByAttrSearch)) {
            $listProduct->whereIn('id', $listProductIdsByAttrSearch);
        }

        $listProductId = $listProduct->pluck('id')->toArray();

        $listProduct = $listProduct->paginate(10);

        $listBanner = Banner::where('status', 1)->get();

        return view('web.search.index', compact('search', 'listProduct', 'listCategory', 'listBanner', 'listProductId'));
    }

    public function about()
    {
        return view('web.about.index');
    }

    public function contact()
    {
        return view('web.contact.index');
    }

    public function recommend(Request $request)
    {

        $data = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        $items = Product::with('category:id,name')
            ->where('category_id', $data['category_id'])
            ->where('quantity', '>', 0)            // chỉ lấy hàng còn
            ->orderBy('name')
            ->take(24)                              // giới hạn trả về
            ->get();

        return response()->json([
            'items' => $items->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => (int) $p->price,
                    'stock' => (int) $p->quantity,
                    'category_name' => optional($p->category)->name,
                    'thumbnail' => $p->image ?? null,
                    'url' => route('web.detail', $p->id),
                ];
            }),
        ]);
    }

    public function searchDish(Request $request)
    {
        $query = $request->get('query');

        if ($query) {
            $dishes = DB::table('cong_thuc')
                ->select('tenmonan')
                ->where('tenmonan', 'LIKE', "%{$query}%")
                ->get();

            return response()->json($dishes);
        }

        return response()->json([]);
    }

    public function getRecipe(Request $request)
    {
        $tenmonan = $request->get('tenmonan');

        if ($tenmonan) {
            $dish = DB::table('cong_thuc')
                ->select('congthuc')
                ->where('tenmonan', $tenmonan)
                ->first();

            if ($dish) {
                return response()->json(['congthuc' => $dish->congthuc]);
            }
        }

        return response()->json(['congthuc' => 'Không tìm thấy công thức.']);
    }

    public function AiRecommend(Request $request)
    {
        $tenmon = trim($request->input('dish_name', ''));
        $congthuc = trim($request->input('dish_recipe', ''));

        // nếu không có congthuc nhưng có tenmon -> cố load từ bảng cong_thuc
        if (!$congthuc && $tenmon) {
            $row = \DB::table('cong_thuc')->where('tenmonan', $tenmon)->first();
            if ($row)
                $congthuc = trim($row->congthuc ?? '');
        }

        // Tách nguyên liệu ra danh sách (human readable lines)
        $ingredients = $this->extractIngredients($congthuc);

        $lines = [];    // kết quả từng nguyên liệu -> products
        $missing = [];  // nguyên liệu không tìm thấy product

        foreach ($ingredients as $line) {
            // tạo các biến thể tìm kiếm từ 1 dòng nguyên liệu
            $variants = $this->generateKeywordVariants($line);

            if (empty($variants)) {
                $missing[] = $line;
                continue;
            }

            // build query tìm product theo nhiều biến thể (OR)

            $query = Product::where(function ($q) use ($variants) {
                foreach ($variants as $v) {
                    $v = trim($v);
                    if (!$v)
                        continue;
                    // dùng orWhere để tăng cơ hội match; cân nhắc fulltext/pg_trgm sau này
                    $q->orWhere('name', 'LIKE', "%{$v}%");
                }
            });

            $products = $query->get(['id', 'name', 'price', 'image']);

            if ($products->isEmpty()) {
                $missing[] = $line;
            }

            // map product để trả về JSON/view-friendly
            $prodArr = $products->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->price ?? null,
                    'image' => $p->image ?? null,
                    'url' => route('web.detail', $p->id),
                ];
            })->toArray();

            $lines[] = [
                'ingredient' => $line,
                'variants' => $variants, // tùy bạn có muốn hiển thị variants
                'products' => $prodArr,
            ];
        }

        // nếu muốn flatten products chung cho hiển thị tổng
        $allProducts = collect([]);
        foreach ($lines as $ln) {
            foreach ($ln['products'] as $p) {
                $allProducts->push($p);
            }
        }
        $allProducts = $allProducts->unique('id')->values();

        return view('web.ai.recommend', [
            'tenmon' => $tenmon,
            'congthuc' => $congthuc,
            'nguyenlieu' => $ingredients,
            'lines' => $lines,              // từng nguyên liệu -> products
            'products' => $allProducts,     // list all suggested products (unique)
            'missing' => array_values(array_unique($missing)),
        ]);
    }



    protected function extractIngredients(string $text): array
    {
        if (!$text)
            return [];

        $text = str_replace(["\r\n", "\r"], "\n", $text);

        $startPos = mb_stripos($text, 'nguyên liệu');
        $endPos = mb_stripos($text, 'cách làm');
        if ($startPos !== false && $endPos !== false && $endPos > $startPos) {
            $segment = mb_substr($text, $startPos, $endPos - $startPos);
        } else {
            $segment = $text;
        }

        $segment = preg_replace('/[\-\•\*]/u', "\n", $segment);

        $rawLines = preg_split("/\n+/", $segment);
        $items = [];

        foreach ($rawLines as $ln) {
            $ln = trim($ln);
            if (!$ln)
                continue;
            // skip headers
            if (preg_match('/nguyên liệu|cách làm|cách chế biến/i', $ln))
                continue;

            $parts = preg_split('/[,;]+/', $ln);
            foreach ($parts as $part) {
                $part = trim($part);
                if (!$part)
                    continue;

                $part = preg_replace('/^[\d\.\)\s]+/', '', $part);

                if (preg_match('/\b(xào|nấu|đun|cho|trộn|phi|kho|đảo|thêm|ướp|dầm|rửa|làm|để)\b/i', $part)) {
                    continue;
                }

                $part = preg_replace('/\s+/', ' ', $part);

                if (preg_match('/[^\d\W_]+/u', $part)) {
                    $items[] = $part;
                }
            }
        }

        $items = array_values(array_filter(array_map('trim', $items)));

        $final = [];
        foreach ($items as $it) {
            // split by ' và ' or ' hoặc ' if present
            $subparts = preg_split('/\b(và|hoặc)\b/u', $it);
            foreach ($subparts as $s) {
                $s = trim($s);
                if (!$s)
                    continue;
                // remove trailing words like 'tươi', 'khô' optionally? keep for now
                $final[] = $s;
            }
        }

        // Normalize spacing and remove empty, unique
        $final = array_values(array_unique(array_map(fn($x) => trim($x), $final)));

        // As a last step, expand compact grouped words like "Me chua" -> keep as is
        return $final;
    }

    protected function generateKeywordVariants(string $line): array
    {
        $orig = trim(mb_strtolower($line));

        // remove parenthesis content
        $orig = preg_replace('/\(.+?\)/u', ' ', $orig);

        // remove quantities and units (common VN units)
        $orig = preg_replace('/\b\d+([.,]\d+)?\s*(kg|g|gram|gr|ml|l|chục|chục|cái|quả|trái|muỗng|thìa|gói|gam|kg|viên|tép|cây|miếng)\b/iu', ' ', $orig);
        // remove isolated numeric weights like 600g / 300g occurrences
        $orig = preg_replace('/\d+(g|kg|ml|l)\b/u', ' ', $orig);

        // remove punctuation
        $orig = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $orig);

        // trim spaces
        $orig = preg_replace('/\s+/', ' ', trim($orig));
        if (!$orig)
            return [];

        // remove stopwords to get core words
        $stopwords = ['tươi', 'khô', 'sơ', 'ít', 'một', 'một ít', 'phi', 'sơ', 'thơm', 'tươi', 'to', 'nhỏ'];
        foreach ($stopwords as $w) {
            $orig = preg_replace('/\b' . preg_quote($w, '/') . '\b/u', ' ', $orig);
        }
        $orig = preg_replace('/\s+/', ' ', trim($orig));
        if (!$orig)
            return [];

        // produce diacritics-stripped variant
        $noAccent = $this->stripVietnameseDiacritics($orig);

        // split words
        $words = explode(' ', $noAccent);
        $count = count($words);

        $candidates = [];

        // push full phrase variants (both with and without diacritics)
        $candidates[] = $orig;
        if ($noAccent !== $orig)
            $candidates[] = $noAccent;

        // push last two words, last word
        if ($count >= 2) {
            $last2 = $words[$count - 2] . ' ' . $words[$count - 1];
            $candidates[] = $last2;
            // also with original diacritics attempt: get last2 from original
            $origWords = preg_split('/\s+/', trim(preg_replace('/\s+/', ' ', $line)));
            $owCount = count($origWords);
            if ($owCount >= 2) {
                $last2orig = mb_strtolower($origWords[$owCount - 2] . ' ' . $origWords[$owCount - 1]);
                $candidates[] = $last2orig;
            }
        }
        // last single word
        $candidates[] = $words[$count - 1];

        // dedupe and limit
        $candidates = array_values(array_unique(array_filter(array_map('trim', $candidates))));
        // keep max 6 variants
        return array_slice($candidates, 0, 6);
    }

    protected function stripVietnameseDiacritics($str)
    {
        $unicode = [
            'a' => 'áàảãạăắằẳẵặâấầẩẫậ',
            'A' => 'ÁÀẢÃẠĂẮẰẲẴẶÂẤẦẨẪẬ',
            'd' => 'đ',
            'D' => 'Đ',
            'e' => 'éèẻẽẹêếềểễệ',
            'E' => 'ÉÈẺẼẸÊẾỀỂỄỆ',
            'i' => 'íìỉĩị',
            'I' => 'ÍÌỈĨỊ',
            'o' => 'óòỏõọôốồổỗộơớờởỡợ',
            'O' => 'ÓÒỎÕỌÔỐỒỔỖỘƠỚỜỞỠỢ',
            'u' => 'úùủũụưứừửữự',
            'U' => 'ÚÙỦŨỤƯỨỪỬỮỰ',
            'y' => 'ýỳỷỹỵ',
            'Y' => 'ÝỲỶỸỴ'
        ];
        foreach ($unicode as $nonAccent => $accented) {
            $arr = preg_split('//u', $accented, null, PREG_SPLIT_NO_EMPTY);
            foreach ($arr as $ch)
                $str = str_replace($ch, $nonAccent, $str);
        }
        return $str;
    }
}
