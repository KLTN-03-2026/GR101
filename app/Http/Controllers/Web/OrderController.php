<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\CheckoutRequest;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function createOrder(CheckoutRequest $request)
    {
        if (!empty($request->get('coupon_id'))) {
            if (getNumberUseFreeCoupon($request->get('coupon_id')) <= 0 || !checkActiveCouponStartAndEnd($request->get('coupon_id'))) {
                $coupon = Coupon::find($request->get('coupon_id'));
                return redirect()->back()->with('error', 'Mã giảm giá [' . $coupon->name . '] không thể sử dụng được nữa');
            }
        }

        $user = Auth::guard('web')->user();
        $dataOrder = $request->except(['list_product', '_token']);
        $listProductRequest = $request->get('list_product');
        $listProduct = Product::whereIn('id', array_column($listProductRequest, 'id'))->get();

        // 1. Check stock for each product before creating order
        foreach ($listProduct as $product) {
            $requestedQty = $listProductRequest[$product->id]['quantity'] ?? 0;
            if ($product->getQuantityActive() < $requestedQty) {
                return redirect()->back()->with('error', 'Sản phẩm [' . $product->name . '] hiện đã hết hàng hoặc không đủ số lượng.');
            }
        }

        $dataOrder['user_id'] = $user->id;
        $dataOrder['created_at'] = Carbon::now();
        $dataOrder['id'] = time() * rand(11, 99); // Simpler ID or keep your logic

        $city = City::find($request->city_id);
        $dataOrder['shipping_fee'] = $city->shipping_fee ?? 0;
        $dataOrder['city_id'] = $request->city_id;
        $orderProductInsert = [];
        
        // Use Transaction to ensure data integrity
        $orderId = DB::transaction(function() use ($dataOrder, $listProduct, $listProductRequest, $user) {
            $orderId = DB::table('orders')->insertGetId($dataOrder);

            foreach ($listProduct as $product) {
                if (!empty($listProductRequest[$product->id]['quantity'])) {
                    DB::table('order_products')->insert([
                        'order_id' => $orderId,
                        'product_id' => $product->id,
                        'price' => $product->price,
                        'quantity' => $listProductRequest[$product->id]['quantity']
                    ]);
                    
                    // Track purchase activity
                    \App\Services\ActivityTracker::trackPurchase($product->id, $listProductRequest[$product->id]['quantity']);
                }
            }

            // Clear items from cart
            DB::table('carts')->whereIn('product_id', array_column($listProductRequest, 'id'))
                ->where('user_id', '=', $user->id)
                ->delete();

            return $orderId;
        });

        $order = Order::find($orderId);

        // Handle Coupon logic (Keeping your existing logic)
        if (!empty($request->get('coupon_id'))) {
            $couponId = $request->get('coupon_id');
            $discount = 0;
            $coupon = Coupon::find($couponId);
            if ($coupon) {
                if ($coupon->type == 'price') {
                    $discount = $coupon->discount;
                } else if ($coupon->type == 'percent') {
                    $discount = $coupon->discount * $order->total() / 100;
                }
                if ($discount > $coupon->discount_max) $discount = $coupon->discount_max;
                $order->coupon_id = $couponId;
                $order->discount = $discount;
                $order->save();
            }
        }

        // 2. Handle Payment Redirects
        if (!empty($dataOrder['payment_type'])) {
            $pType = strtoupper($dataOrder['payment_type']);
            if ($pType == 'VNPAY') {
                // Redirect to VnpayController@create with amount
                return redirect()->route('web.vnpay.create', ['amount' => $order->total(), 'order_id' => $orderId]);
            }
        }

        // 3. Default: COD or Payment initiated
        try {
            Mail::send('emails.create_order', ['order' => $order], function ($mess) use ($order) {
                $mess->to($order->User->email, 'Thông báo')->subject('[' . env('APP_NAME') . ']Thông báo đặt hàng thành công #' . $order->id);
            });
        } catch (\Exception $e) {
            \Log::error('Mail sending failed: ' . $e->getMessage());
        }

        return redirect()->route('web.order_detail', $orderId)->with('success', 'Đặt hàng thành công');
    }

    public function success()
    {
        return view('web.order.success');
    }

    public function error()
    {
        return view('web.order.error');
    }

    public function checkOut(Request $request)
    {
        $listProductRequest = $request->get('list_product');
        
        if (empty($listProductRequest) || !is_array($listProductRequest)) {
            // Fallback: Get all products from cart if no specific selection
            $user = Auth::guard('web')->user();
            $cartItems = DB::table('carts')->where('user_id', $user->id)->get();
            $listProductRequest = [];
            foreach ($cartItems as $item) {
                $listProductRequest[$item->product_id] = [
                    'id' => $item->product_id,
                    'quantity' => $item->quantity
                ];
            }
        }

        $productIds = array_filter(array_column($listProductRequest, 'id'));
        $listProduct = Product::whereIn('id', $productIds)->get();
        $listCity = City::all();

        $total = 0;
        foreach ($listProduct as $product) {
            $qty = intval($listProductRequest[$product->id]['quantity'] ?? 0);
            if ($qty > 0) {
                $total += $product->price * $qty;
            }
        }

        $listCoupon = getListCouponForCheckOut($total);

        return view('web.checkout.index', compact('listProductRequest', 'listProduct', 'total', 'listCoupon', 'listCity'));
    }

    public function listOrderOfUser()
    {
        $listOrder = Order::orderBy('created_at', 'desc')->where('user_id', Auth::guard('web')->user()->id)->paginate(10);

        return view('web.order.index', compact('listOrder'));
    }

    public function orderDetail(Request $request, int $id)
    {
        $order = Order::where('user_id', Auth::guard('web')->user()->id)->find($id);

        if (!$order) {
            abort(404);
        }

        return view('web.order.detail', compact('order'));
    }

    public function updateStatusOrder(Request $request, int $id)
    {
        $order = Order::where('user_id', Auth::guard('web')->user()->id)->find($id);

        if (!$order) {
            abort(404);
        }

        if (!empty($request->get('status'))) {
            if ($request->get('status') == 'REFUND' && $order->status == 'SUCCESS') {
                if (!empty($order->success_at) && getDayFromDateToDate($order->success_at, \Carbon\Carbon::now()) <= 7) {
                    $order->status = $request->get('status');
                    $order->save();
                }
            } else {
                $order->status = $request->get('status');
                $order->save();
            }
        }

        return redirect()->back()->with('success', 'Cập nhật thành công');
    }

}
