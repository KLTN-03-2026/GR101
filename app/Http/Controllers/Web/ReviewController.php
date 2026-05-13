<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        if (!Auth::guard('web')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thực hiện đánh giá.'
            ], 401);
        }

        $userId = Auth::guard('web')->id();

        // Check if user already reviewed this product
        $existingReview = Review::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đánh giá sản phẩm này rồi.'
            ], 400);
        }

        Review::create([
            'user_id' => $userId,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 1 // Auto-approve for now, or you can set to 0 to wait for admin approval
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn bạn đã đánh giá sản phẩm!'
        ]);
    }
}
