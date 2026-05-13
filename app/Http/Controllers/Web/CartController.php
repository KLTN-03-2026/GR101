<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addCart(Request $request)
    {
        $productId = $request->get('product_id');
        $userId = Auth::guard('web')->user()->id;
        $quantity = $request->get('quantity') ?? 1;
        $quantityNew = $request->get('quantity_new') ?? null;
        $product = DB::table('products')->where('id', $productId);
        $productExists = $product->exists();

        if (!$productExists) {
            return response()->json([
                'success' => false,
                'data' => [
                    'message' => 'Sản phẩm không tồn tại',
                    'qty' => \Illuminate\Support\Facades\Auth::guard('web')->user()->countListProductInCart(),
                ]
            ]);
        }

        $productPrice = $product->get()->first()->price ?? null;
        $productQuantity = Product::find($productId)->getQuantityActive() ?? null;

        if ($quantity < 1 || !is_numeric($quantity) || ($quantityNew != null && $quantityNew <= 0)) {
            return response()->json([
                'success' => false,
                'data' => [
                    'message' => 'Số lượng không hợp lệ',
                    'qty' => \Illuminate\Support\Facades\Auth::guard('web')->user()->countListProductInCart(),
                ]
            ]);
        }

        $cart = DB::table('carts')
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        $quantityUpdate = 0;

        if (!empty($cart)) {
            $quantityUpdate = $quantityNew ?? ($cart->quantity + $quantity);

            if ($productQuantity < $quantityUpdate) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'message' => 'Số lượng trong kho không đủ',
                        'qty' => \Illuminate\Support\Facades\Auth::guard('web')->user()->countListProductInCart(),
                    ]
                ]);
            }

            DB::table('carts')
                ->where('user_id', '=', $userId)
                ->where('product_id', '=', $productId)
                ->update([
                    'quantity' => $quantityUpdate
                ]);
        } else {
            $quantityUpdate = $quantity;
            if ($productQuantity < $quantityUpdate) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        'message' => 'Số lượng trong kho không đủ',
                        'qty' => \Illuminate\Support\Facades\Auth::guard('web')->user()->countListProductInCart(),
                    ]
                ]);
            }

            DB::table('carts')->insert([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantityUpdate,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Track add to cart activity
            \App\Services\ActivityTracker::trackAddToCart($productId, $quantityUpdate);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'message' => 'Cập nhật giỏ hàng thành công',
                'qty' => \Illuminate\Support\Facades\Auth::guard('web')->user()->countListProductInCart(),
                'total' => formatVnd(\Illuminate\Support\Facades\Auth::guard('web')->user()->totalMoneyInCart()),
                'total_row' => $productPrice * $quantityUpdate
            ]
        ]);
    }

    public function addBulkCart(Request $request)
    {
        $productIds = $request->get('product_ids');
        if (empty($productIds) || !is_array($productIds)) {
            return response()->json(['success' => false, 'message' => 'Không có sản phẩm nào được chọn.']);
        }

        $userId = Auth::guard('web')->user()->id;
        $successCount = 0;
        $errorMessages = [];

        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if (!$product || $product->status != 1) {
                $errorMessages[] = "Sản phẩm ID {$productId} không tồn tại hoặc đã ngừng bán.";
                continue;
            }

            if ($product->getQuantityActive() < 1) {
                $errorMessages[] = "Sản phẩm '{$product->name}' đã hết hàng.";
                continue;
            }

            $cart = DB::table('carts')
                ->where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if (!empty($cart)) {
                $newQuantity = $cart->quantity + 1;
                if ($product->getQuantityActive() < $newQuantity) {
                    $errorMessages[] = "Số lượng '{$product->name}' trong kho không đủ.";
                    continue;
                }
                DB::table('carts')
                    ->where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->update(['quantity' => $newQuantity]);
            } else {
                DB::table('carts')->insert([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $successCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Đã thêm thành công {$successCount} sản phẩm vào giỏ hàng.",
            'errors' => $errorMessages,
            'qty' => Auth::guard('web')->user()->countListProductInCart()
        ]);
    }

    public function deleteProductCart(Request $request)
    {
        $productId = $request->get('product_id');
        $user = Auth::guard('web')->user();

        // Get quantity before deleting
        $cart = DB::table('carts')
            ->where('product_id', $productId)
            ->where('user_id', $user->id)
            ->first();

        $quantity = $cart ? $cart->quantity : 0;

        DB::table('carts')
            ->where('product_id', $productId)
            ->where('user_id', $user->id)
            ->delete();

        // Track remove from cart activity
        \App\Services\ActivityTracker::trackRemoveFromCart($productId, $quantity);

        return response()->json([
            'success' => true,
            'data' => [
                'qty' => $user->countListProductInCart(),
                'total' => $user->totalMoneyInCart()
            ]
        ]);
    }

    public function deleteSelected(Request $request)
    {
        $productIds = $request->get('product_ids');
        if (empty($productIds) || !is_array($productIds)) {
            return response()->json(['success' => false, 'message' => 'Không có sản phẩm nào được chọn.']);
        }

        $user = Auth::guard('web')->user();

        DB::table('carts')
            ->whereIn('product_id', $productIds)
            ->where('user_id', $user->id)
            ->delete();

        return response()->json([
            'success' => true,
            'data' => [
                'qty' => $user->countListProductInCart(),
                'total' => $user->totalMoneyInCart()
            ]
        ]);
    }

    public function clearCart()
    {
        $user = Auth::guard('web')->user();
        DB::table('carts')->where('user_id', $user->id)->delete();

        return response()->json([
            'success' => true,
            'data' => [
                'qty' => 0,
                'total' => 0
            ]
        ]);
    }

    public function listProductInCart()
    {
        $listProduct = Auth::guard('web')->user()->listProductInCart;
        $total = Auth::guard('web')->user()->totalMoneyInCart();
        return view('web.cart.list', compact('listProduct', 'total'));
    }

    public function checkCoupon(Request $request)
    {
        $code = strtoupper($request->get('code'));
        $total = $request->get('total');
        
        $coupon = \App\Models\Coupon::where('name', $code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Mã không tồn tại.'
            ]);
        }

        if (getNumberUseFreeCoupon($coupon->id) <= 0 || !checkActiveCouponStartAndEnd($coupon->id)) {
            return response()->json([
                'success' => false,
                'message' => 'Mã không hợp lệ hoặc hết hạn.'
            ]);
        }

        $discount = 0;
        if ($coupon->type == 'price') {
            $discount = $coupon->discount;
        } else if ($coupon->type == 'percent') {
            $discount = ($total * $coupon->discount) / 100;
        }

        if ($discount > $coupon->discount_max) {
            $discount = $coupon->discount_max;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $coupon->id,
                'discount' => $discount,
                'message' => 'Áp dụng mã thành công!'
            ]
        ]);
    }
}
