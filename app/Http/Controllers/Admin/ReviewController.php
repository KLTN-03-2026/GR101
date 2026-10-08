<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews.
     */
    public function index()
    {
        $reviews = Review::with(['User', 'Product'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Update the specified review in storage (Change Status).
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $review->status = $review->status == 1 ? 0 : 1;
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái thành công!',
            'status' => $review->status
        ]);
    }

    /**
     * Reply to the specified review.
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'reply_content' => 'required|string|max:1000',
        ]);

        $review = Review::findOrFail($id);
        $review->reply_content = $request->reply_content;
        $review->reply_at = now();
        $review->save();

        return redirect()->back()->with('success', 'Đã gửi phản hồi thành công!');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Đã xóa đánh giá thành công!');
    }
}
