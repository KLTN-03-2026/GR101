<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VnpayController extends Controller
{
    private const VNP_URL         = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    private const VNP_RETURN_URL  = '/vnpay/return'; 
    private const VNP_TMN_CODE    = 'PYB0LHE2';
    private const VNP_HASH_SECRET = 'DXN1SR5O0VF54D7TLWEPSE9CG0E3ESPW';

    /**
     * GET /vnpay/create?amount=123000[&bankcode=NCB]
     * Tạo URL thanh toán và chuyển hướng sang VNPAY.
     */
    public function create(Request $request)
    {
        $amount = (int) $request->query('amount', 0); // đơn vị VND
        if ($amount <= 0) {
            return back()->with('error', 'Số tiền không hợp lệ.');
        }

        session(['vnp_return_to' => $request->headers->get('referer') ?? url('/')]);

        $vnp_TxnRef     = (string) mt_rand(100000, 999999);
        $vnp_OrderInfo  = 'Thanh toán đơn hàng';
        $vnp_OrderType  = 'billpayment';
        $vnp_Amount     = $amount * 100; 
        $vnp_Locale     = 'vn';
        $vnp_IpAddr     = $request->ip();
        $vnp_ReturnUrl  = url(self::VNP_RETURN_URL); 
        $vnp_BankCode   = $request->query('bankcode'); 

        $input = [
            'vnp_Version'    => '2.1.0',
            'vnp_TmnCode'    => self::VNP_TMN_CODE,
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
        $secureHash = hash_hmac('sha512', implode('&', $hashData), self::VNP_HASH_SECRET);

        $redirectUrl = self::VNP_URL . '?' . implode('&', $query) . '&vnp_SecureHash=' . $secureHash;

        return redirect()->away($redirectUrl);
    }


    public function return(Request $request)
    {
        $params = $request->query();

        $vnp_SecureHash = $params['vnp_SecureHash'] ?? '';
        unset($params['vnp_SecureHash'], $params['vnp_SecureHashType']);

        ksort($params);
        $hashPieces = [];
        foreach ($params as $k => $v) {
            $hashPieces[] = urlencode($k) . '=' . urlencode($v);
        }
        $myHash = hash_hmac('sha512', implode('&', $hashPieces), self::VNP_HASH_SECRET);

        $returnTo = session('vnp_return_to', url('/'));
        session()->forget('vnp_return_to');

        if (!hash_equals($myHash, $vnp_SecureHash)) {
            return redirect()->to($returnTo)->with('error', 'Chữ ký không hợp lệ!');
        }

        $code = $request->query('vnp_ResponseCode'); // '00' = Thành công
        if ($code === '00') {
            return redirect()->to($returnTo)->with('success', 'Thanh toán VNPAY thành công!');
        }

        return redirect()->to($returnTo)->with('error', 'Thanh toán thất bại/đã hủy (mã: ' . $code . ').');
    }
}
