<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VnpayController extends Controller
{
    private function getVnpConfig()
    {
        return [
            'vnp_Url'         => env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
            'vnp_TmnCode'     => env('VNP_TMN_CODE', '27042022'),
            'vnp_HashSecret'  => env('VNP_HASH_SECRET', 'A58042404C99217A60E4EE7745198170'),
            'vnp_ReturnUrl'   => url(env('VNP_RETURN_URL', '/vnpay/return')),
        ];
    }

    /**
     * GET /vnpay/create?amount=123000[&bankcode=NCB]
     * Tạo URL thanh toán và chuyển hướng sang VNPAY.
     */
    public function create(Request $request)
    {
        $config = $this->getVnpConfig();
        $amount = (int) $request->query('amount', 0); // đơn vị VND
        if ($amount <= 0) {
            return back()->with('error', 'Số tiền không hợp lệ.');
        }

        session(['vnp_return_to' => $request->headers->get('referer') ?? url('/')]);

        $orderId = $request->query('order_id', (string) mt_rand(100000, 999999));
        $vnp_TxnRef     = (string) $orderId;
        $vnp_OrderInfo  = 'Thanh toan don hang ' . $orderId;
        $vnp_OrderType  = 'billpayment';
        $vnp_Amount     = $amount * 100; 
        $vnp_Locale     = 'vn';
        $vnp_IpAddr     = $request->ip();
        $vnp_ReturnUrl  = $config['vnp_ReturnUrl']; 
        $vnp_BankCode   = $request->query('bankcode'); 

        $input = [
            'vnp_Version'    => '2.1.0',
            'vnp_TmnCode'    => $config['vnp_TmnCode'],
            'vnp_Amount'     => $vnp_Amount,
            'vnp_Command'    => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode'   => 'VND',
            'vnp_IpAddr'     => $vnp_IpAddr,
            'vnp_Locale'     => $vnp_Locale,
            'vnp_OrderInfo'  => $vnp_OrderInfo,
            'vnp_OrderType'  => $vnp_OrderType,
            'vnp_ReturnUrl'  => $vnp_ReturnUrl, 
            'vnp_TxnRef'     => $vnp_TxnRef,
        ];
        if (!empty($vnp_BankCode)) {
            $input['vnp_BankCode'] = $vnp_BankCode;
        }

        // Ký tham số
        ksort($input);
        $query    = [];
        $hashData = [];
        foreach ($input as $k => $v) {
            $query[]    = urlencode($k) . '=' . urlencode($v);
            $hashData[] = urlencode($k) . '=' . urlencode($v);
        }
        $secureHash = hash_hmac('sha512', implode('&', $hashData), $config['vnp_HashSecret']);

        $redirectUrl = $config['vnp_Url'] . '?' . implode('&', $query) . '&vnp_SecureHash=' . $secureHash;

        return redirect()->away($redirectUrl);
    }


    public function return(Request $request)
    {
        $config = $this->getVnpConfig();
        $params = $request->query();

        $vnp_SecureHash = $params['vnp_SecureHash'] ?? '';
        unset($params['vnp_SecureHash'], $params['vnp_SecureHashType']);

        ksort($params);
        $hashPieces = [];
        foreach ($params as $k => $v) {
            $hashPieces[] = urlencode($k) . '=' . urlencode($v);
        }
        $myHash = hash_hmac('sha512', implode('&', $hashPieces), $config['vnp_HashSecret']);

        $returnTo = session('vnp_return_to', url('/'));
        session()->forget('vnp_return_to');

        if (!hash_equals($myHash, $vnp_SecureHash)) {
            return redirect()->to($returnTo)->with('error', 'Chữ ký không hợp lệ!');
        }

        $code   = $request->query('vnp_ResponseCode'); // '00' = Thành công
        $txnRef = $request->query('vnp_TxnRef');        // order_id

        if ($code === '00') {
            if (!empty($txnRef)) {
                DB::table('orders')
                    ->where('id', '=', $txnRef)
                    ->update([
                        'payment_status'   => 'PAID',
                        'payment_response' => json_encode($request->toArray())
                    ]);

                // Gửi email xác nhận (giống MoMo)
                try {
                    $order = \App\Models\Order::find($txnRef);
                    if ($order) {
                        \Illuminate\Support\Facades\Mail::send(
                            'emails.create_order',
                            ['order' => $order],
                            function ($mess) use ($order) {
                                $mess->to($order->User->email, 'Thông báo')
                                     ->subject('[' . env('APP_NAME') . '] Thanh toán thành công #' . $order->id);
                            }
                        );
                    }
                } catch (\Exception $e) {
                    \Log::error('Mail sending failed in vnpayReturn: ' . $e->getMessage());
                }
            }

            // Redirect về trang chi tiết đơn hàng
            return redirect()->route('web.order_detail', $txnRef)
                             ->with('success', 'Thanh toán VNPAY thành công!');
        }

        // Thanh toán thất bại: về trang chi tiết đơn hàng với thông báo lỗi
        if (!empty($txnRef)) {
            return redirect()->route('web.order_detail', $txnRef)
                             ->with('error', 'Thanh toán thất bại hoặc đã hủy (mã lỗi: ' . $code . ').');
        }

        return redirect()->route('web.home')
                         ->with('error', 'Thanh toán thất bại (mã lỗi: ' . $code . ').');
    }
}
