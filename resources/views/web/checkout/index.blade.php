@extends('layouts.master_user')

@section('content')
<style>
    /* Ép ẩn Nice Select cho ô tỉnh thành */
    .city-select-wrapper .nice-select { 
        display: none !important; 
    }
    /* Hiển thị và định dạng lại ô chọn gốc */
    #city_id.ignore-nice-select {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
        color: #111827 !important;
        background-color: #f9fafb !important;
        border: 1px solid #d1d5db !important;
        -webkit-appearance: menulist !important;
        appearance: menulist !important;
        width: 100% !important;
        height: 56px !important;
        border-radius: 1rem !important;
        padding-left: 1rem !important;
        font-weight: 700 !important;
    }
</style>
<div class="bg-gray-50 min-h-screen font-sans pb-20">
    <div class="breadcrumb-area py-10">
        <div class="container">
            <nav class="flex text-xs font-bold uppercase tracking-widest text-gray-400 gap-4">
                <a href="{{ route('web.index') }}" class="hover:text-green-900 transition-colors">Trang chủ</a>
                <i class="fa fa-chevron-right text-[8px] mt-0.5"></i>
                <a href="{{ route('web.list.product.cart') }}" class="hover:text-green-900 transition-colors">Giỏ hàng</a>
                <i class="fa fa-chevron-right text-[8px] mt-0.5"></i>
                <span class="text-gray-900">Thanh toán</span>
            </nav>
        </div>
    </div>

    <div class="container">
        <form action="{{ route('web.create.order') }}" method="post" id="form-checkout">
            @csrf
            <input type="hidden" name="coupon_id" id="coupon_id">
            
            <div class="row">
                <!-- Left: Shipping Information -->
                <div class="col-lg-7">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10 space-y-10">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-soft-green rounded-2xl flex items-center justify-center text-green-900">
                                <i class="fa fa-shipping-fast text-xl"></i>
                            </div>
                            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Thông tin giao hàng</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-gray-500 uppercase tracking-widest ml-2">Họ và tên *</label>
                                <input type="text" name="name" value="{{ Auth::guard('web')->user()->name }}" required
                                       class="w-full bg-gray-50 border border-gray-300 rounded-2xl p-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-green-900/10 focus:bg-white transition-all outline-none">
                                @error('name') <p class="text-xs text-red-500 mt-1 ml-2 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-[11px] font-black text-gray-500 uppercase tracking-widest ml-2">Số điện thoại *</label>
                                <input type="text" name="phone" value="{{ Auth::guard('web')->user()->phone }}" required
                                       class="w-full bg-gray-50 border border-gray-300 rounded-2xl p-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-green-900/10 focus:bg-white transition-all outline-none">
                                @error('phone') <p class="text-xs text-red-500 mt-1 ml-2 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-md-12 space-y-2">
                                <label class="text-[11px] font-black text-gray-500 uppercase tracking-widest ml-2">Email *</label>
                                <input type="email" name="email" value="{{ Auth::guard('web')->user()->email }}" required
                                       class="w-full bg-gray-50 border border-gray-300 rounded-2xl p-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-green-900/10 focus:bg-white transition-all outline-none">
                                @error('email') <p class="text-xs text-red-500 mt-1 ml-2 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="col-md-12 space-y-2">
                                <label class="text-[11px] font-black text-gray-500 uppercase tracking-widest ml-2">Tỉnh / Thành phố *</label>
                                <select name="city_id" id="city_id" required
                                        class="ignore-nice-select w-full bg-gray-50 border border-gray-300 rounded-2xl text-sm font-bold text-gray-900 outline-none"
                                        style="padding: 14px 16px; color: #111827 !important; height: auto;">
                                    <option value="">--- Chọn tỉnh thành ---</option>
                                    @foreach ($listCity as $city)
                                        <option data-shipping-fee="{{ $city->shipping_fee }}"
                                                value="{{ $city->id }}"
                                                {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }} — Phí ship: {{ formatVnd($city->shipping_fee) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                    <p class="text-xs text-red-500 mt-1 ml-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-12 space-y-2">
                                <label class="text-[11px] font-black text-gray-500 uppercase tracking-widest ml-2">Địa chỉ chi tiết *</label>
                                <input type="text" name="address" value="{{ Auth::guard('web')->user()->address }}" placeholder="Số nhà, tên đường, phường/xã..." required
                                       class="w-full bg-gray-50 border border-gray-300 rounded-2xl p-4 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-green-900/10 focus:bg-white transition-all outline-none">
                            </div>

                            <div class="col-md-12 space-y-2 pt-4">
                                <label class="text-[11px] font-black text-gray-500 uppercase tracking-widest ml-2">Ghi chú đơn hàng</label>
                                <textarea name="note" rows="4" placeholder="Lời nhắn cho cửa hàng hoặc shipper..."
                                          class="w-full bg-gray-50 border border-gray-300 rounded-[2rem] p-6 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-green-900/10 focus:bg-white transition-all outline-none"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods Section -->
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10 mt-8 space-y-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-soft-green rounded-2xl flex items-center justify-center text-green-900">
                                <i class="fa fa-credit-card text-xl"></i>
                            </div>
                            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Phương thức thanh toán</h2>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <label class="payment-option relative flex items-center gap-4 p-6 bg-gray-50 rounded-2xl border-2 border-transparent cursor-pointer hover:bg-gray-100 transition-all active:scale-[0.98]">
                                <input type="radio" name="payment_type" value="COD" checked class="hidden peer">
                                <div class="w-6 h-6 rounded-full border-2 border-gray-200 peer-checked:border-green-600 peer-checked:bg-green-600 flex items-center justify-center transition-all">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                                <div class="flex-1 flex items-center gap-4">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-green-700">
                                        <i class="fa fa-hand-holding-usd text-lg"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-gray-900">Thanh toán khi nhận hàng (COD)</span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Tiền mặt sẽ được thu tại địa chỉ của bạn</span>
                                    </div>
                                </div>
                            </label>

                            <label class="payment-option relative flex items-center gap-4 p-6 bg-gray-50 rounded-2xl border-2 border-transparent cursor-pointer hover:bg-gray-100 transition-all active:scale-[0.98]">
                                <input type="radio" name="payment_type" value="VNPAY" class="hidden peer">
                                <div class="w-6 h-6 rounded-full border-2 border-gray-200 peer-checked:border-green-600 peer-checked:bg-green-600 flex items-center justify-center transition-all">
                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                </div>
                                <div class="flex-1 flex items-center gap-4">
                                    <img src="{{ asset('images/vnpay.png') }}" class="w-10 h-10 object-contain bg-white rounded-xl shadow-sm p-1">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-gray-900">Thanh toán qua VNPAY</span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Thanh toán an toàn qua QR, Thẻ nội địa, Ví điện tử</span>
                                    </div>
                                </div>
                            </label>


                        </div>
                    </div>
                </div>

                <!-- Right: Order Summary -->
                <div class="col-lg-5 mt-10 lg:mt-0">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10 sticky top-10 space-y-8">
                        <h2 class="text-xl font-black text-gray-900 tracking-tight">Tóm tắt đơn hàng</h2>

                        <!-- Product List -->
                        <div class="space-y-6 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach ($listProduct as $product)
                                @if (!empty($listProductRequest[$product->id]['quantity']))
                                    <div class="flex items-center gap-4 py-4 border-b border-gray-50 last:border-0 group">
                                        <div class="w-16 h-16 bg-gray-50 rounded-2xl p-2 flex-shrink-0 group-hover:scale-105 transition-transform">
                                            <img src="{{ $product->getImage() }}" class="w-full h-full object-contain">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-black text-gray-900 truncate group-hover:text-green-900 transition-colors">{{ $product->getName() }}</h4>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">
                                                {{ formatVnd($product->price) }} × {{ $listProductRequest[$product->id]['quantity'] }}
                                            </p>
                                        </div>
                                        <span class="text-sm font-black text-gray-900 whitespace-nowrap">
                                            {{ formatVnd($product->price * $listProductRequest[$product->id]['quantity']) }}
                                        </span>
                                        <input type="hidden" name="list_product[{{ $product->id }}][id]" value="{{ $product->id }}">
                                        <input type="hidden" name="list_product[{{ $product->id }}][quantity]" value="{{ $listProductRequest[$product->id]['quantity'] }}">
                                    </div>
                                @endif
                                @endforeach
                        </div>

                        <!-- Coupons Area: Manual input always visible -->
                        <div class="pt-6 border-t border-gray-100">
                            <label class="text-[11px] font-black text-gray-500 uppercase tracking-widest block mb-4">
                                <i class="fa fa-tag text-green-600 mr-1"></i> Mã khuyến mãi
                            </label>

                            <!-- Manual Input - luôn hiển thị -->
                            <div class="flex gap-3 mb-4">
                                <div class="flex-1 relative">
                                    <input type="text" id="coupon_code_input"
                                           placeholder="Nhập mã giảm giá..."
                                           class="w-full bg-gray-50 border border-gray-300 rounded-2xl p-4 pr-12 text-sm font-bold text-gray-900 focus:ring-2 focus:ring-green-900/10 focus:bg-white transition-all outline-none uppercase tracking-widest">
                                    <i class="fa fa-ticket-alt absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                </div>
                                <button type="button" onclick="applyCouponManual()"
                                        class="px-6 bg-green-900 text-white rounded-2xl font-black text-xs tracking-widest uppercase hover:bg-green-800 transition-all active:scale-95 shadow-sm whitespace-nowrap">
                                    <i class="fa fa-check mr-1"></i> Áp dụng
                                </button>
                            </div>

                            <!-- Thông báo kết quả áp dụng mã -->
                            <div id="coupon-result" class="hidden text-xs font-bold rounded-xl px-4 py-3 mb-4"></div>

                            @if(count($listCoupon) > 0)
                                <label class="text-[9px] font-bold text-gray-400 uppercase tracking-[0.2em] block mb-3">Mã khuyến mãi khả dụng của bạn</label>
                                <div class="grid grid-cols-2 gap-3">
                                    @foreach ($listCoupon as $coupon)
                                        <button type="button"
                                                class="coupon-select p-4 bg-gray-50 rounded-2xl border-2 border-gray-100 hover:border-green-400 transition-all text-left group"
                                                data-id="{{ $coupon->id }}"
                                                data-code="{{ $coupon->name }}"
                                                data-discount-max="{{ $coupon->discount_max }}"
                                                @if ($coupon->type == 'price') data-discount="{{ $coupon->discount }}"
                                                @elseif($coupon->type == 'percent') data-discount="{{ ($total * $coupon->discount) / 100 }}" @endif>
                                            <div class="flex items-center gap-2 mb-1">
                                                <i class="fa fa-tag text-green-600 text-[10px]"></i>
                                                <span class="text-xs font-black text-green-800 uppercase tracking-tight">{{ $coupon->name }}</span>
                                            </div>
                                            <div class="text-[10px] font-bold text-gray-500 leading-tight">
                                                @if ($coupon->type == 'price') Giảm {{ formatVnd($coupon->discount) }}
                                                @else Giảm {{ $coupon->discount }}% (tối đa {{ formatVnd($coupon->discount_max) }}) @endif
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Totals Area -->
                        <div class="space-y-4 pt-8 border-t border-gray-200">
                            <div class="flex justify-between items-center text-sm border-b border-gray-50 pb-3">
                                <span class="font-bold text-gray-500 uppercase tracking-widest text-[11px]">Tạm tính</span>
                                <span class="font-black text-gray-900">{{ formatVnd($total) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm shipping-row hidden border-b border-gray-50 pb-3">
                                <span class="font-bold text-gray-500 uppercase tracking-widest text-[11px]">Phí vận chuyển</span>
                                <span class="font-black text-gray-900" id="shipping-fee">0 VNĐ</span>
                            </div>
                            <div class="flex justify-between items-center text-sm discount-row hidden border-b border-gray-50 pb-3">
                                <span class="font-bold text-orange-500 uppercase tracking-widest text-[11px]">Giảm giá</span>
                                <span class="font-black text-orange-600" id="discount-amount">0 VNĐ</span>
                            </div>
                            <div class="flex justify-between items-center py-6">
                                <span class="font-black text-gray-900 uppercase tracking-tighter text-lg">Tổng tiền</span>
                                <span class="font-black text-4xl text-green-900 tracking-tighter" id="grand-total" data-base="{{ $total }}">
                                    {{ formatVnd($total) }} VNĐ
                                </span>
                            </div>
                        </div>

                        <button type="submit" form="form-checkout" class="w-full bg-green-900 text-white py-5 rounded-2xl font-black text-sm tracking-[0.2em] hover:bg-black transition-all shadow-xl active:scale-95 uppercase">
                            Xác nhận đặt hàng
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .payment-option input:checked + div {
        border-color: #14532d;
        background-color: #14532d;
    }
    .payment-option input:checked ~ div {
        /* Highlighting when checked */
    }
    .coupon-select.active {
        border-color: #14532d;
        background-color: #f0fdf4;
    }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f8fafc;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
</style>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Force destroy nice-select for city selection
        if ($.fn.niceSelect) {
            $('#city_id').niceSelect('destroy');
        }
        
        let shippingFee = 0;
        let discountAmount = 0;
        const totalBase = parseFloat($('#grand-total').attr('data-base'));

        function updateGrandTotal() {
            const grandTotal = totalBase + shippingFee - discountAmount;
            $('#grand-total').text(formatVnd(grandTotal) + ' VNĐ');
        }

        // Handle City Selection for Shipping Fee
        $('#city_id').change(function() {
            const selected = $(this).find('option:selected');
            const fee = parseFloat(selected.attr('data-shipping-fee') || 0);
            
            if (fee > 0) {
                shippingFee = fee;
                $('.shipping-row').removeClass('hidden');
                $('#shipping-fee').text(formatVnd(fee));
            } else {
                shippingFee = 0;
                $('.shipping-row').addClass('hidden');
            }
            updateGrandTotal();
        });

        // Handle Coupon Selection from list
        $('.coupon-select').click(function() {
            const code = $(this).attr('data-code');

            if ($(this).hasClass('active')) {
                // Bỏ chọn: reset tất cả
                $(this).removeClass('active border-green-400').addClass('border-gray-100');
                discountAmount = 0;
                $('.discount-row').addClass('hidden');
                $('#coupon_id').val('');
                $('#coupon_code_input').val('');
                $('#coupon-result').addClass('hidden');
                updateGrandTotal();
            } else {
                // Điền mã vào ô nhập và tự động áp dụng
                $('#coupon_code_input').val(code);
                applyCouponManual();
            }
        });

        window.applyCouponManual = function() {
            const code = $('#coupon_code_input').val().trim();
            const $result = $('#coupon-result');

            if (!code) {
                $result.removeClass('hidden bg-green-50 text-green-700 bg-red-50 text-red-700')
                       .addClass('bg-yellow-50 text-yellow-700')
                       .html('<i class="fa fa-exclamation-circle mr-1"></i> Vui lòng nhập mã giảm giá')
                       .removeClass('hidden');
                return;
            }

            $.LoadingOverlay('show');
            $.ajax({
                url: '{{ route("web.check_coupon") }}',
                method: 'get',
                data: { code: code, total: totalBase },
                success: function(res) {
                    $.LoadingOverlay('hide');
                    if (res.success) {
                        discountAmount = res.data.discount;
                        $('.discount-row').removeClass('hidden');
                        $('#discount-amount').text('- ' + formatVnd(discountAmount));
                        $('#coupon_id').val(res.data.id);

                        // Highlight selected coupon in list
                        $('.coupon-select').removeClass('active border-green-400').addClass('border-gray-100');
                        $(`.coupon-select[data-id="${res.data.id}"]`).addClass('active border-green-400').removeClass('border-gray-100');

                        // Show inline success message
                        $result.removeClass('hidden bg-red-50 text-red-700 bg-yellow-50 text-yellow-700')
                               .addClass('bg-green-50 text-green-700')
                               .html('<i class="fa fa-check-circle mr-1"></i> Áp dụng thành công! ' + res.data.message)
                               .removeClass('hidden');

                        updateGrandTotal();
                    } else {
                        // Show inline error message
                        $result.removeClass('hidden bg-green-50 text-green-700 bg-yellow-50 text-yellow-700')
                               .addClass('bg-red-50 text-red-700')
                               .html('<i class="fa fa-times-circle mr-1"></i> ' + res.message)
                               .removeClass('hidden');
                    }
                },
                error: function() {
                    $.LoadingOverlay('hide');
                    $result.removeClass('hidden bg-green-50 text-green-700 bg-yellow-50 text-yellow-700')
                           .addClass('bg-red-50 text-red-700')
                           .html('<i class="fa fa-times-circle mr-1"></i> Có lỗi xảy ra, vui lòng thử lại.')
                           .removeClass('hidden');
                }
            });
        }

        // Form Submission with Loading
        $('#form-checkout').submit(function() {
            $.LoadingOverlay('show');
        });
    });
</script>
@endsection
