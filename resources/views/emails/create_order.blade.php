<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đơn hàng</title>
</head>
<body style="font-family: 'Inter', Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 30px 10px;">
    <div style="max-width: 650px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
        
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #064e3b 0%, #10b981 100%); color: #ffffff; padding: 40px 30px; text-align: center;">
            <h1 style="margin: 0; font-size: 28px; font-weight: 800; letter-spacing: 0.5px;">Xác Nhận Đơn Hàng</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px; opacity: 0.9;">Cảm ơn bạn đã tin tưởng mua sắm tại Nông Sản Xanh!</p>
        </div>

        <!-- Body -->
        <div style="padding: 40px 30px;">
            <p style="font-size: 16px; color: #374151; margin-bottom: 25px; line-height: 1.6;">
                Xin chào, đơn hàng <strong>#{{ $order->id }}</strong> của bạn đã được hệ thống ghi nhận. Dưới đây là thông tin chi tiết:
            </p>

            <!-- Order Info Table -->
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 35px;">
                <tbody>
                    <tr>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb; color: #6b7280; width: 40%; font-size: 15px;">Ngày đặt hàng:</td>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb; color: #111827; font-weight: 600; font-size: 15px;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb; color: #6b7280; font-size: 15px;">Số điện thoại:</td>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb; color: #111827; font-weight: 600; font-size: 15px;">{{ $order->phone }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb; color: #6b7280; font-size: 15px;">Địa chỉ nhận hàng:</td>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb; color: #111827; font-weight: 600; font-size: 15px; line-height: 1.5;">{{ $order->address }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb; color: #6b7280; font-size: 15px;">Phương thức thanh toán:</td>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb; color: #111827; font-weight: 600; font-size: 15px;">{{ $order->payment_type }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb; color: #6b7280; font-size: 15px;">Trạng thái đơn hàng:</td>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb;">
                            <span style="background-color: #fef08a; color: #854d0e; padding: 6px 14px; border-radius: 9999px; font-weight: 700; font-size: 13px; text-transform: uppercase;">
                                {{ strip_tags(mapOrderStatus($order->status)) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb; color: #6b7280; font-size: 15px;">Tình trạng thanh toán:</td>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb;">
                            @if(strpos(strip_tags(mapStringIsPaid($order->payment_status)), 'Chưa') !== false)
                                <span style="color: #ef4444; font-weight: 700; font-size: 15px;">{{ strip_tags(mapStringIsPaid($order->payment_status)) }}</span>
                            @else
                                <span style="color: #10b981; font-weight: 700; font-size: 15px;">{{ strip_tags(mapStringIsPaid($order->payment_status)) }}</span>
                            @endif
                        </td>
                    </tr>
                    @if($order->note)
                    <tr>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb; color: #6b7280; font-size: 15px;">Ghi chú của bạn:</td>
                        <td style="padding: 14px 0; border-bottom: 1px dashed #e5e7eb; color: #111827; font-weight: 600; font-size: 15px; font-style: italic;">{{ $order->note }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <h2 style="font-size: 18px; color: #111827; border-left: 4px solid #10b981; padding-left: 10px; margin-bottom: 20px; font-weight: 800;">Chi Tiết Sản Phẩm</h2>

            <div style="border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background-color: #f8fafc;">
                        <tr>
                            <th style="text-align: left; padding: 14px 16px; color: #64748b; font-weight: 700; font-size: 13px; text-transform: uppercase;">Sản phẩm</th>
                            <th style="text-align: center; padding: 14px 16px; color: #64748b; font-weight: 700; font-size: 13px; text-transform: uppercase; width: 15%;">SL</th>
                            <th style="text-align: right; padding: 14px 16px; color: #64748b; font-weight: 700; font-size: 13px; text-transform: uppercase; width: 25%;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->Products as $product)
                        <tr>
                            <td style="padding: 16px; border-top: 1px solid #e5e7eb; color: #1e293b; font-weight: 600; font-size: 15px;">
                                {{ $product->getName() }}
                            </td>
                            <td style="padding: 16px; border-top: 1px solid #e5e7eb; color: #475569; text-align: center; font-size: 15px;">
                                x{{ $product->pivot->quantity }}
                            </td>
                            <td style="padding: 16px; border-top: 1px solid #e5e7eb; color: #1e293b; text-align: right; font-weight: 700; font-size: 15px;">
                                {{ formatVnd($product->pivot->price * $product->pivot->quantity) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals -->
            <div style="margin-top: 30px; border-top: 2px solid #f1f5f9; padding-top: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tbody>
                        <tr>
                            <td style="padding: 8px 0; text-align: right; color: #64748b; font-size: 15px; width: 60%;">Tổng tiền hàng:</td>
                            <td style="padding: 8px 0; text-align: right; color: #1e293b; font-weight: 700; font-size: 15px;">{{ formatVnd($order->total(false) - $order->shipping_fee) }}</td>
                        </tr>
                        @if(!empty($order->Coupon->name))
                        <tr>
                            <td style="padding: 8px 0; text-align: right; color: #64748b; font-size: 15px;">Giảm giá ({{ $order->Coupon->name }}):</td>
                            <td style="padding: 8px 0; text-align: right; color: #ef4444; font-weight: 700; font-size: 15px;">- {{ formatVnd($order->discount) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="padding: 8px 0; text-align: right; color: #64748b; font-size: 15px;">Phí vận chuyển:</td>
                            <td style="padding: 8px 0; text-align: right; color: #1e293b; font-weight: 700; font-size: 15px;">{{ formatVnd(+$order->shipping_fee) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 20px 0 10px 0; text-align: right; color: #0f172a; font-weight: 900; font-size: 18px; text-transform: uppercase;">
                                Tổng thanh toán:
                            </td>
                            <td style="padding: 20px 0 10px 0; text-align: right; color: #10b981; font-weight: 900; font-size: 22px;">
                                {{ formatVnd($order->total()) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <!-- Footer -->
        <div style="background-color: #f8fafc; padding: 25px; text-align: center; border-top: 1px solid #e2e8f0;">
            <p style="margin: 0; color: #64748b; font-size: 14px; line-height: 1.6;">
                Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua số hotline <strong style="color: #10b981;">0123.456.789</strong> hoặc phản hồi trực tiếp email này.
            </p>
            <p style="margin: 15px 0 0 0; color: #94a3b8; font-size: 13px; font-weight: 500;">
                © 2026 Nông Sản Xanh. All rights reserved.
            </p>
        </div>

    </div>
</body>
</html>
