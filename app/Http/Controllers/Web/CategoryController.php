<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categoryDetail(Request $request, $id){
        $categoryIds = $request->get('category_ids', [$id]); // Mặc định là category hiện tại
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $sortBy = $request->get('sort_by', 'latest');

        $listCategory = Category::all();
        $category = Category::find($id);
        
        $query = Product::with(['Category'])->where('status', 1);

        // Nếu lọc theo danh mục khác thì lấy theo danh mục đó, còn không mặc định theo ID hiện tại
        if ($request->has('category_ids')) {
            $query->whereIn('category_id', $request->get('category_ids'));
        } else {
            $query->where('category_id', $id);
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
                $query->orderBy('id', 'desc');
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

        return view('web.category.detail', compact('category', 'listProduct', 'listCategory', 'listBanner', 'listProductId'));
    }
}
