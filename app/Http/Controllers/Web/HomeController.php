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
        $categoryIds = $request->get('category_ids');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $sortBy = $request->get('sort_by', 'latest');

        // Track search activity
        if ($search) {
            \App\Services\ActivityTracker::trackSearch($search);
        }

        $listCategory = Category::all();

        $query = Product::where('status', 1);

        // Search by keyword
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Filter by categories
        if ($categoryIds && is_array($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        // Filter by price
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        // Sorting
        switch ($sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'best_selling':
                $query->orderBy('id', 'desc'); // Tạm thời dùng ID desc hoặc bạn có thể thêm logic đếm sold_count
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $listProduct = $query->paginate(12);

        // Handle AJAX request
        if ($request->ajax()) {
            return response()->json([
                'html' => view('web.include.product_list_partial', compact('listProduct'))->render(),
                'total' => $listProduct->total()
            ]);
        }

        $listBanner = Banner::where('status', 1)->get();
        $listProductId = $listProduct->pluck('id')->toArray();

        return view('web.search.index', compact('search', 'listProduct', 'listCategory', 'listBanner', 'listProductId'));
    }

    public function autocomplete(Request $request)
    {
        $search = $request->get('query');
        $products = Product::where('status', 1)
            ->where('name', 'like', '%' . $search . '%')
            ->take(5)
            ->get(['id', 'name', 'image', 'price']);

        return response()->json($products->map(function($p) {
            return [
                'id' => $p->id,
                'name' => $p->name,
                'image' => $p->image,
                'price' => number_format($p->price, 0, ',', '.') . 'đ',
                'url' => route('web.detail', $p->id)
            ];
        }));
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

    public function AiRecommend(Request $request, \App\Services\OpenAIService $aiService)
    {
        // Nhận tham số từ AJAX (JSON body)
        $tenmon = trim($request->input('tenmon', ''));
        $congthuc = trim($request->input('congthuc', ''));

        if (mb_strlen($tenmon) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Vui long nhap ten mon an de AI goi y.'
            ], 422);
        }

        // Nếu không có công thức, thử tìm trong DB
        if (empty($congthuc) && !empty($tenmon)) {
            $row = \DB::table('cong_thuc')
                ->where('tenmonan', $tenmon)
                ->orWhere('tenmonan', 'LIKE', "%{$tenmon}%")
                ->first();
            if ($row) {
                $tenmon = trim($row->tenmonan ?? $tenmon);
                $congthuc = trim($row->congthuc ?? '');
            }
        }

        // Lấy tất cả sản phẩm đang bán
        $availableProducts = Product::where('status', 1)->get(['id', 'name']);
        $productCatalog = $availableProducts->map(function($p) {
            return "ID: {$p->id} | Tên: {$p->name}";
        })->implode("\n");

        // Gửi Prompt cho Gemini (Prompt Engineering)
        $prompt = "Bạn là một đầu bếp chuyên nghiệp và trợ lý mua sắm thông minh.
Nhiệm vụ của bạn là hỗ trợ người dùng nấu món: '{$tenmon}'.

Yêu cầu cụ thể:
1. Nếu tôi có cung cấp 'Công thức/Yêu cầu' dưới đây, hãy dựa vào đó. 
2. NẾU 'Công thức/Yêu cầu' ĐỂ TRỐNG, bạn hãy tự dùng kiến thức chuyên môn của mình để đưa ra một công thức chuẩn, ngon nhất cho món ăn này.

Công thức/Yêu cầu hiện tại: " . ($congthuc ?: "Đang trống, hãy tự đề xuất công thức chuẩn.") . "

Danh sách sản phẩm hiện có trong siêu thị của tôi:
{$productCatalog}

Bạn phải trả về kết quả dưới định dạng JSON duy nhất (không có văn bản thừa) như sau:
{
  \"ten_mon\": \"Tên món ăn\",
  \"mo_ta\": \"Mô tả ngắn gọn về hương vị món ăn\",
  \"nguyen_lieu\": [\"Tên nguyên liệu 1\", \"Tên nguyên liệu 2\"],
  \"cach_lam\": [\"Bước 1...\", \"Bước 2...\"],
  \"san_pham_ids\": [ID1, ID2, ID3] (Hãy khớp các nguyên liệu với danh sách sản phẩm hiện có ở trên, chọn ID sản phẩm phù hợp nhất)
}";

        $ingredientNames = $this->extractIngredients($congthuc);
        $matchedIds = [];
        $moTa = "";
        $cachLam = $this->extractRecipeSteps($congthuc);
        $tenMonAi = $tenmon;
        $source = 'gemini';

        try {
            $aiResponse = $aiService->generateContent($prompt, true);
            
            // Log raw response for debugging
            \Log::info("Gemini Raw Response: " . substr($aiResponse, 0, 500));

            // Robust JSON extraction
            $startPos = strpos($aiResponse, '{');
            $endPos = strrpos($aiResponse, '}');
            $aiData = null;

            if ($startPos !== false && $endPos !== false) {
                $jsonString = substr($aiResponse, $startPos, $endPos - $startPos + 1);
                $aiData = json_decode($jsonString, true);
            }
            
            if (!$aiData) {
                 \Log::error("Failed to decode JSON from Gemini. Raw: " . $aiResponse);
                 throw new \Exception("Invalid JSON from AI");
            }

            $ingredientNames = $this->normalizeStringList($aiData['nguyen_lieu'] ?? $ingredientNames);
            $matchedIds = $this->normalizeIdList($aiData['san_pham_ids'] ?? []);
            $moTa = $aiData['mo_ta'] ?? "Một món ăn tuyệt vời cho bữa cơm gia đình.";
            $cachLam = $this->normalizeStringList($aiData['cach_lam'] ?? $cachLam);
            $tenMonAi = $aiData['ten_mon'] ?? $tenmon;
            $source = $aiData['source'] ?? $source;

        } catch (\Exception $e) {
            \Log::warning("AI Recommend Fallback triggered: " . $e->getMessage());
            $source = 'fallback';
            $ingredientNames = $this->extractIngredients($congthuc);
            if (empty($ingredientNames)) {
                $ingredientNames = [$tenmon];
            }
            $moTa = "Gợi ý nguyên liệu và cách nấu cho món {$tenmon}.";
            $cachLam = ["Sơ chế sạch các nguyên liệu và chế biến theo khẩu vị gia đình."];
        }

        // LUÔN tìm sản phẩm nếu chưa có (Logic tìm kiếm thông minh và nghiêm ngặt)
        if (empty($matchedIds)) {
            $stopWords = [
                'chiên', 'xào', 'nấu', 'canh', 'kho', 'luộc', 'hấp', 'nướng', 'bóp', 'gỏi', 'trộn', 
                'món', 'với', 'cho', 'và', 'các', 'những', 'tươi', 'sống', 'ngon', 'đặc', 'biệt', 'chuẩn',
                'chien', 'xao', 'nau', 'canh', 'kho', 'luoc', 'hap', 'nuong', 'bop', 'goi', 'tron'
            ];

            // 1. Tìm theo cụm nguyên liệu (Ưu tiên nhất)
            foreach ($ingredientNames as $name) {
                $cleanName = mb_strtolower(trim($name));
                // Loại bỏ phần định lượng (ví dụ: "500g đậu hũ" -> "đậu hũ")
                $cleanName = preg_replace('/\b\d+([.,]\d+)?\s*(kg|g|gram|gr|ml|l|cái|quả|trái|muỗng|thìa|gói|gam|viên|tép|cây|miếng)\b/iu', '', $cleanName);
                $cleanName = trim(preg_replace('/\s+/', ' ', $cleanName));

                if (mb_strlen($cleanName) < 2) continue;
                if (in_array($cleanName, $stopWords)) continue;

                $found = Product::where('status', 1)
                    ->where('name', 'LIKE', "%{$cleanName}%")
                    ->limit(2)
                    ->pluck('id')
                    ->toArray();
                $matchedIds = array_merge($matchedIds, $found);
            }

            // 2. Tìm theo cụm từ ghép từ tên món (Ví dụ: "đậu hũ" từ "đậu hũ chiên")
            $dishWords = preg_split('/\s+/', mb_strtolower($tenmon));
            $cleanWords = array_filter($dishWords, fn($w) => !in_array($w, $stopWords) && mb_strlen($w) > 2);
            
            if (count($cleanWords) >= 2) {
                $wordsArray = array_values($cleanWords);
                for ($i = 0; $i < count($wordsArray) - 1; $i++) {
                    $phrase = $wordsArray[$i] . ' ' . $wordsArray[$i+1];
                    $found = Product::where('status', 1)
                        ->where('name', 'LIKE', "%{$phrase}%")
                        ->limit(2)
                        ->pluck('id')
                        ->toArray();
                    $matchedIds = array_merge($matchedIds, $found);
                }
            }

            // 3. Cuối cùng mới tìm theo từ đơn lẻ nhưng chỉ với các từ quan trọng
            if (empty($matchedIds)) {
                foreach ($cleanWords as $w) {
                    $found = Product::where('status', 1)
                        ->where('name', 'LIKE', "%{$w}%")
                        ->limit(1)
                        ->pluck('id')
                        ->toArray();
                    $matchedIds = array_merge($matchedIds, $found);
                }
            }

            $matchedIds = array_values(array_unique($matchedIds));
        }

        // Lấy thông tin các sản phẩm đã match và map dữ liệu cho frontend
        if (empty($ingredientNames)) {
            $ingredientNames = [$tenMonAi ?: $tenmon];
        }

        $matchedIds = array_values(array_unique(array_merge(
            $matchedIds,
            $this->findMatchingProductIds($ingredientNames, $tenMonAi ?: $tenmon)
        )));

        $products = [];
        if (!empty($matchedIds)) {
            $products = Product::whereIn('id', $matchedIds)
                ->where('status', 1)
                ->get()
                ->map(function($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'price' => (float)$p->price,
                        'thumbnail' => $p->getImage(),
                        'stock' => max(0, (int) $p->getQuantityActive()),
                        'category_name' => optional($p->Category)->name,
                        'url' => route('web.detail', $p->id)
                    ];
                })->toArray();
        }

        return response()->json([
            'success' => true,
            'source' => $source,
            'ten_mon' => $tenMonAi ?: $tenmon,
            'mo_ta' => $moTa,
            'congthuc' => $congthuc,
            'nguyen_lieu' => array_values($ingredientNames),
            'cach_lam' => array_values($cachLam),
            'items' => array_values($products)
        ]);
    }



    protected function normalizeStringList($value): array
    {
        if (is_string($value)) {
            $value = preg_split('/[\n;,]+/', $value);
        }

        if (!is_array($value)) {
            return [];
        }

        $items = [];
        foreach ($value as $item) {
            $item = trim((string) $item);
            if ($item !== '' && mb_strlen($item) <= 180) {
                $items[] = $item;
            }
        }

        return array_values(array_unique($items));
    }

    protected function normalizeIdList($ids): array
    {
        if (!is_array($ids)) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map('intval', $ids), fn($id) => $id > 0)));
    }

    protected function findMatchingProductIds(array $ingredientNames, string $dishName): array
    {
        $ids = [];
        $terms = [];

        foreach (array_merge($ingredientNames, [$dishName]) as $text) {
            $terms = array_merge($terms, $this->generateKeywordVariants($text));
        }

        foreach (array_values(array_unique($terms)) as $term) {
            $found = Product::where('status', 1)
                ->where('name', 'LIKE', "%{$term}%")
                ->limit(3)
                ->pluck('id')
                ->toArray();

            $ids = array_merge($ids, $found);
        }

        return array_values(array_unique($ids));
    }

    protected function extractRecipeSteps(string $text): array
    {
        if (!$text) {
            return [];
        }

        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $segment = $text;
        foreach (['cach lam', 'cach nau', 'cÃ¡ch lÃ m', 'cÃ¡ch náº¥u'] as $marker) {
            $pos = mb_stripos($text, $marker);
            if ($pos !== false) {
                $segment = mb_substr($text, $pos);
                break;
            }
        }

        $segment = preg_replace('/[\-\â€¢\*]/u', "\n", $segment);
        $lines = preg_split("/\n+/", $segment);
        $steps = [];

        foreach ($lines as $line) {
            $line = trim($line);
            $line = preg_replace('/^(cach lam|cach nau|cÃ¡ch lÃ m|cÃ¡ch náº¥u|bÆ°á»›c\s*\d+)\s*[:\.\-]?\s*/iu', '', $line);
            $line = preg_replace('/^\d+[\.\)]\s*/', '', $line);

            if ($line && mb_strlen($line) > 8) {
                $steps[] = $line;
            }
        }

        return array_slice(array_values(array_unique($steps)), 0, 8);
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

        $candidates = [];

        // push full phrase variants
        $candidates[] = $orig;
        
        // Thêm biến thể cuối cùng sau khi xóa bớt từ thừa (ví dụ: "rau cải ngọt" -> "cải ngọt")
        $origWords = preg_split('/\s+/', trim(preg_replace('/\s+/', ' ', $line)));
        $owCount = count($origWords);
        if ($owCount >= 2) {
            $last2orig = mb_strtolower($origWords[$owCount - 2] . ' ' . $origWords[$owCount - 1]);
            $candidates[] = $last2orig;
        }

        // dedupe and limit
        $candidates = array_values(array_unique(array_filter(array_map('trim', $candidates))));
        // keep max 4 variants
        return array_slice($candidates, 0, 4);
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
