@extends('layouts.master_user')

@section('content')
<div class="bg-gray-50 min-h-screen font-sans">
    <div class="breadcrumb-area py-10">
        <div class="container">
            <nav class="flex text-xs font-bold uppercase tracking-widest text-gray-400 gap-4">
                <a href="{{ route('web.index') }}" class="hover:text-green-900 transition-colors">Trang chủ</a>
                <i class="fa fa-chevron-right text-[8px] mt-0.5"></i>
                <span class="text-gray-900">Giỏ hàng</span>
            </nav>
        </div>
    </div>

    <div class="pb-20">
        <div class="container">
            @if(count($listProduct) > 0)
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Product List Card -->
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-8 border-b border-gray-50 flex items-center justify-between flex-wrap gap-4">
                                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Giỏ hàng của bạn</h1>
                                <div class="flex items-center gap-6">
                                    <!-- Select All -->
                                    <div class="flex items-center gap-3 cursor-pointer group/all" id="select-all-container">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" id="check-all" class="appearance-none w-5 h-5 border-2 border-gray-300 rounded-md checked:bg-green-900 checked:border-green-900 transition-all cursor-pointer">
                                            <i class="fa fa-check absolute text-[8px] text-white opacity-0 peer-checked:opacity-100 left-1.5 pointer-events-none"></i>
                                        </div>
                                        <span class="text-[10px] font-black text-gray-500 group-hover/all:text-green-900 uppercase tracking-widest transition-colors">Chọn tất cả</span>
                                    </div>

                                    <button type="button" id="remove-selected-cart" class="text-[10px] font-black text-red-500 hover:text-red-700 uppercase tracking-widest transition-colors flex items-center gap-2 opacity-50 cursor-not-allowed" disabled>
                                        <i class="fa fa-trash"></i> Xóa mục đã chọn
                                    </button>

                                    <span class="bg-soft-green text-green-900 text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-tighter">
                                        <span class="cart-item-count">{{ count($listProduct) }}</span> Sản phẩm
                                    </span>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100 border-b border-gray-200">
                                            <th class="px-8 py-5 text-left text-[11px] font-black text-gray-500 uppercase tracking-widest">Chọn</th>
                                            <th class="px-8 py-5 text-left text-[11px] font-black text-gray-500 uppercase tracking-widest">Sản phẩm</th>
                                            <th class="px-8 py-5 text-center text-[11px] font-black text-gray-500 uppercase tracking-widest">Số lượng</th>
                                            <th class="px-8 py-5 text-right text-[11px] font-black text-gray-500 uppercase tracking-widest">Thành tiền</th>
                                            <th class="px-8 py-5 text-center text-[11px] font-black text-gray-500 uppercase tracking-widest">Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($listProduct as $product)
                                            <tr class="group hover:bg-gray-50 transition-colors border-b border-gray-100">
                                                <td class="px-8 py-6">
                                                    <div class="relative flex items-center product-checkbox-wrapper">
                                                        <input type="checkbox" data-product-id="{{ $product->id }}" 
                                                               class="ckb-product peer appearance-none w-6 h-6 border-2 border-gray-300 rounded-lg checked:bg-green-900 checked:border-green-900 transition-all cursor-pointer opacity-0 group-hover:opacity-100 checked:opacity-100" 
                                                               name="list_product[{{ $product->id }}][id]" value="{{ $product->id }}" form="form-main">
                                                        <i class="fa fa-check absolute text-[10px] text-white opacity-0 peer-checked:opacity-100 left-1.5 pointer-events-none transition-opacity"></i>
                                                    </div>
                                                </td>
                                                <td class="px-8 py-6">
                                                    <div class="flex items-center gap-6">
                                                        <div class="w-20 h-20 bg-gray-50 rounded-2xl p-2 flex-shrink-0">
                                                            <img src="{{ $product->getImage() }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
                                                        </div>
                                                        <div class="space-y-1">
                                                            <a href="{{ route('web.detail', $product->id) }}" class="text-sm font-black text-gray-900 hover:text-green-900 transition-colors">{{ $product->getName() }}</a>
                                                            <p class="text-xs font-bold text-gray-400">{{ formatVnd($product->price) }} / sản phẩm</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-8 py-6">
                                                    <div class="flex items-center justify-center">
                                                        <div class="flex items-center bg-gray-100 rounded-2xl p-1.5 border border-gray-300 shadow-sm">
                                                            <button type="button" class="qty-btn dec w-8 h-8 flex items-center justify-center text-gray-500 hover:text-red-500 transition-colors" data-product-id="{{ $product->id }}">
                                                                <i class="fa fa-minus text-[10px]"></i>
                                                            </button>
                                                            <input type="number" min="1" class="quantity-product-row w-12 text-center bg-transparent border-none text-sm font-black text-gray-900 focus:ring-0" 
                                                                   data-product-id="{{ $product->id }}"
                                                                   data-quantity-base="{{ $product->pivot->quantity }}"
                                                                   name="list_product[{{ $product->id }}][quantity]"
                                                                   form="form-main"
                                                                   value="{{ $product->pivot->quantity }}">
                                                            <button type="button" class="qty-btn inc w-8 h-8 flex items-center justify-center text-gray-500 hover:text-green-600 transition-colors" data-product-id="{{ $product->id }}">
                                                                <i class="fa fa-plus text-[10px]"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-8 py-6 text-right">
                                                    <span class="amount text-base font-black text-gray-900" 
                                                          data-total="{{ $product->price * $product->pivot->quantity }}" 
                                                          data-product-id="{{ $product->id }}">
                                                        {{ formatVnd($product->price * $product->pivot->quantity) }}
                                                    </span>
                                                </td>
                                                <td class="px-8 py-6 text-center">
                                                    <button type="button" class="remove-product-cart px-4 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all flex items-center gap-2 mx-auto border border-red-100" data-product-id="{{ $product->id }}">
                                                        <i class="fa fa-trash text-xs"></i>
                                                        <span class="text-[10px] font-black uppercase tracking-widest">Xóa</span>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-10 lg:mt-0">
                        <!-- Summary Card -->
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10 sticky top-10">
                            <h2 class="text-xl font-black text-gray-900 mb-8 tracking-tight">Thanh toán</h2>
                            
                            <div class="space-y-4 mb-8">
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Tạm tính</span>
                                    <span id="subtotal" class="text-sm font-black text-gray-900">0 VNĐ</span>
                                </div>
                                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                    <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Giảm giá</span>
                                    <span class="text-sm font-black text-green-600">0 VNĐ</span>
                                </div>
                                <div class="flex items-center justify-between py-6">
                                    <span class="text-base font-black text-gray-900 uppercase tracking-tighter">Tổng cộng</span>
                                    <span id="total-cart" class="text-3xl font-black text-green-900">0 VNĐ</span>
                                </div>
                            </div>

                            <form action="{{ route('web.checkout.order') }}" method="get" id="form-main">
                                <button type="submit" class="w-full bg-green-900 text-white py-5 rounded-2xl font-black text-sm tracking-[0.2em] hover:bg-black transition-all shadow-xl active:scale-95 uppercase">
                                    Tiến hành thanh toán
                                </button>
                            </form>

                            <a href="{{ route('web.index') }}" class="flex items-center justify-center gap-3 mt-6 text-xs font-black text-gray-400 hover:text-gray-900 transition-colors tracking-widest uppercase">
                                <i class="fa fa-arrow-left text-[10px]"></i>
                                Quay lại mua sắm
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty Cart State -->
                <div class="max-w-2xl mx-auto text-center py-20 bg-white rounded-[3rem] shadow-sm border border-gray-50">
                    <div class="w-32 h-32 bg-soft-green rounded-full flex items-center justify-center mx-auto mb-10 shadow-inner">
                        <i class="fa fa-shopping-basket text-4xl text-green-800"></i>
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 mb-4 tracking-tight">Giỏ hàng đang trống</h2>
                    <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.3em] mb-12">Hãy lấp đầy giỏ hàng bằng những nông sản tươi sạch của chúng tôi</p>
                    <a href="{{ route('web.index') }}" class="inline-block bg-green-900 text-white px-12 py-5 rounded-2xl font-black text-sm tracking-[0.2em] hover:bg-black transition-all shadow-xl active:scale-95 uppercase">
                        Tiếp tục mua sắm
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function () {
        function rfTotal() {
            let total = 0;
            $('.ckb-product:checked').each(function () {
                const productId = $(this).attr('data-product-id');
                total += parseFloat($(`.amount[data-product-id='${productId}']`).attr('data-total'));
            });

            const formattedTotal = formatVnd(total);
            $('#total-cart').text(formattedTotal + ' VNĐ');
            $('#subtotal').text(formattedTotal + ' VNĐ');
        }

        // Initial Total
        $('.ckb-product').prop('checked', true);
        $('#check-all').prop('checked', true);
        updateActionButtons();
        rfTotal();

        $('.ckb-product').change(function () {
            rfTotal();
            updateActionButtons();
            
            // Update check-all status
            const allChecked = $('.ckb-product').length === $('.ckb-product:checked').length;
            $('#check-all').prop('checked', allChecked);
        });

        $('#check-all').change(function() {
            $('.ckb-product').prop('checked', $(this).is(':checked'));
            rfTotal();
            updateActionButtons();
        });

        function updateActionButtons() {
            const checkedCount = $('.ckb-product:checked').length;
            const btn = $('#remove-selected-cart');
            if (checkedCount > 0) {
                btn.removeClass('opacity-50 cursor-not-allowed').addClass('hover:text-red-700').prop('disabled', false);
            } else {
                btn.addClass('opacity-50 cursor-not-allowed').removeClass('hover:text-red-700').prop('disabled', true);
            }
        }

        $('#form-main').submit(function (event) {
            if ($('.ckb-product:checked').length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi...',
                    text: 'Bạn chưa chọn sản phẩm nào để thanh toán'
                });
                event.preventDefault();
            }
        });

        // Quantity Buttons
        $('.qty-btn').click(function() {
            const productId = $(this).attr('data-product-id');
            const input = $(`.quantity-product-row[data-product-id='${productId}']`);
            let currentQty = parseInt(input.val());

            if ($(this).hasClass('inc')) {
                currentQty += 1;
                updateQuantity(productId, currentQty);
            } else {
                if (currentQty > 1) {
                    currentQty -= 1;
                    updateQuantity(productId, currentQty);
                } else if (currentQty === 1) {
                    confirmDelete(productId);
                }
            }
        });

        $('.quantity-product-row').on('change', function() {
            const productId = $(this).attr('data-product-id');
            let newQty = parseInt($(this).val());
            
            if (isNaN(newQty) || newQty < 1) {
                newQty = 1;
                $(this).val(1);
            }
            
            updateQuantity(productId, newQty);
        });

        function updateQuantity(productId, quantity) {
            $.LoadingOverlay('show');
            $.ajax({
                url: @json(route('web.cart.add')),
                method: 'get',
                data: {
                    product_id: productId,
                    quantity_new: quantity
                },
                success: function (res) {
                    $.LoadingOverlay('hide');
                    if (res.success) {
                        const input = $(`.quantity-product-row[data-product-id='${productId}']`);
                        input.val(quantity);
                        input.attr('data-quantity-base', quantity);
                        
                        $(`.amount[data-product-id='${productId}']`).text(formatVnd(res.data.total_row) + ' VNĐ');
                        $(`.amount[data-product-id='${productId}']`).attr('data-total', res.data.total_row);
                        
                        $('.cart-item-count').text(res.data.qty);
                        rfTotal();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: res.data.message
                        });
                    }
                }
            });
        }

        function confirmDelete(productId) {
            Swal.fire({
                title: 'Xóa sản phẩm?',
                text: "Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#14532d',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Đúng, xóa nó!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteProduct(productId);
                }
            });
        }

        function deleteProduct(productId) {
            $.LoadingOverlay('show');
            $.ajax({
                url: @json(route('web.delete.product.cart')),
                method: 'get',
                data: { product_id: productId },
                success: function (res) {
                    $.LoadingOverlay('hide');
                    if (res.success) {
                        $(`tr:has([data-product-id="${productId}"])`).fadeOut(300, function() {
                            $(this).remove();
                            $('.cart-item-count').text(res.data.qty);
                            if ($('tbody tr').length === 0) {
                                window.location.reload();
                            }
                            rfTotal();
                            updateActionButtons();
                        });
                    }
                }
            });
        }

        $('.remove-product-cart').click(function () {
            confirmDelete($(this).attr('data-product-id'));
        });

        $('#remove-selected-cart').click(function () {
            const productIds = [];
            $('.ckb-product:checked').each(function() {
                productIds.push($(this).attr('data-product-id'));
            });

            if (productIds.length === 0) return;

            Swal.fire({
                title: 'Xóa các mục đã chọn?',
                text: "Các sản phẩm đang được chọn sẽ bị loại bỏ khỏi giỏ hàng.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#14532d',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Đúng, xóa chúng!',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.LoadingOverlay('show');
                    $.ajax({
                        url: @json(route('web.delete.selected.cart')),
                        method: 'get',
                        data: { product_ids: productIds },
                        success: function (res) {
                            $.LoadingOverlay('hide');
                            if (res.success) {
                                productIds.forEach(id => {
                                    $(`tr:has([data-product-id="${id}"])`).remove();
                                });
                                
                                $('.cart-item-count').text(res.data.qty);
                                if ($('tbody tr').length === 0) {
                                    window.location.reload();
                                }
                                rfTotal();
                                updateActionButtons();
                                Swal.fire('Đã xóa!', 'Các sản phẩm đã được loại bỏ.', 'success');
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endsection
