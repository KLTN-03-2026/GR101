@extends('layouts.master_user')
@section('content')
    <div class="container py-4">

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 mb-10 mt-4">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-16 h-16 bg-soft-green rounded-2xl flex items-center justify-center text-3xl">🍳</div>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">{{ $tenmon }}</h1>
                    <p class="text-green-700 font-bold text-sm uppercase tracking-widest mt-1">Gợi ý từ Siêu đầu bếp AI</p>
                </div>
            </div>

            @if(!empty($mo_ta))
                <div class="bg-gray-50/80 p-6 rounded-2xl border-l-4 border-green-900 mb-8 italic text-gray-600">
                    "{{ $mo_ta }}"
                </div>
            @endif

            <div class="row">
                <div class="col-lg-5">
                    <div class="h-full bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                        <h5 class="text-lg font-black text-gray-900 mb-6 flex items-center gap-3">
                            <i class="fa fa-shopping-basket text-green-900"></i> Nguyên liệu cần có
                        </h5>
                        <ul class="space-y-3">
                            @foreach ($nguyenlieu as $nl)
                                <li class="flex items-center gap-3 text-gray-600 font-medium">
                                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                    {{ $nl }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="col-lg-7 mt-8 lg:mt-0">
                    <div class="h-full bg-gray-900 text-white p-8 rounded-3xl shadow-xl">
                        <h5 class="text-lg font-black mb-8 flex items-center gap-3">
                            <i class="fa fa-fire text-orange-500"></i> Các bước chế biến
                        </h5>
                        <div class="space-y-6">
                            @if(!empty($cach_lam))
                                @foreach($cach_lam as $index => $step)
                                    <div class="flex gap-4">
                                        <span class="flex-shrink-0 w-8 h-8 bg-green-900 text-white rounded-full flex items-center justify-center font-black text-xs">{{ $index + 1 }}</span>
                                        <p class="text-gray-300 text-sm leading-relaxed">{{ $step }}</p>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-400 italic">Dùng các nguyên liệu tươi sạch của chúng tôi để nấu món ăn này nhé!</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12 border-t border-gray-100 pt-16">
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-4">
                    <span class="w-2 h-10 bg-green-900 rounded-full"></span>
                    Sản phẩm nên mua
                </h2>
                <p class="text-gray-400 font-bold text-[10px] uppercase tracking-widest mt-2">Dựa trên công thức AI vừa đề xuất cho bạn</p>
            </div>
            @if (!$products->isEmpty())
                <button type="button" id="ai-add-all-to-cart" class="bg-green-900 text-white px-8 py-4 rounded-full font-black text-[11px] tracking-widest uppercase shadow-xl hover:bg-black transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                    <i class="fa fa-cart-plus text-sm"></i> Thêm tất cả vào giỏ
                </button>
            @endif
        </div>

        @if ($products->isEmpty())
            <div class="text-center py-20 bg-gray-50 rounded-[3rem] border border-gray-100">
                <i class="fa fa-search text-4xl text-gray-200 mb-4"></i>
                <p class="text-gray-500 font-bold">Rất tiếc, cửa hàng hiện chưa có sẵn các nguyên liệu này.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-10">
                @foreach ($products as $product)
                    @php
                        $prod = is_array($product) ? (object)$product : $product;
                    @endphp
                    <div class="group h-full">
                        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500 flex flex-col h-full overflow-hidden group-hover:-translate-y-2">
                            <div class="relative aspect-square overflow-hidden bg-gray-50/50 p-6 flex items-center justify-center">
                                <a href="{{ is_array($product) ? $prod->url : route('web.detail', $prod->id) }}" class="w-full h-full block">
                                    <img src="{{ is_array($product) ? asset($prod->image) : $prod->getImage() }}" 
                                         alt="{{ $prod->name }}" 
                                         class="w-full h-full object-contain transition-transform duration-700 group-hover:scale-110">
                                </a>
                                <div class="absolute top-4 left-4">
                                    <span class="bg-white/90 backdrop-blur-sm text-green-800 text-[10px] font-black px-3 py-1 rounded-full shadow-sm border border-green-100 uppercase tracking-tighter">
                                        {{ $prod->Category->name ?? 'Nguyên liệu' }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 flex flex-col flex-1 space-y-4">
                                <h3 class="font-bold text-gray-900 text-sm leading-snug h-10 overflow-hidden group-hover:text-green-900 transition-colors">
                                    <a href="{{ is_array($product) ? $prod->url : route('web.detail', $prod->id) }}">
                                        {{ $prod->name }}
                                    </a>
                                </h3>

                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-xl font-black text-gray-900 leading-none">
                                        {{ is_array($product) ? number_format($prod->price, 0, ',', '.') : formatVnd($prod->getPrice()) }}
                                    </span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">VNĐ</span>
                                </div>

                                <button type="button" 
                                        onclick="globalAddCart({{ $prod->id }})"
                                        class="w-full bg-soft-green text-green-900 py-3 rounded-xl font-black text-[10px] tracking-widest uppercase hover:bg-green-900 hover:text-white transition-all flex items-center justify-center gap-2">
                                    <i class="fa fa-plus-circle"></i> Chọn mua
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    $('#ai-add-all-to-cart').off('click').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let products = [];
        
        $('.ai-item-add-cart').each(function() {
            products.push($(this).data('id'));
        });

        if(products.length === 0) return;

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý...');
        $.LoadingOverlay('show');

        let successCount = 0;
        let failCount = 0;
        let requests = products.map(pid => {
            return new Promise((resolve) => {
                $.ajax({
                    url: '{{ route("web.cart.add") }}',
                    method: 'GET',
                    data: { product_id: pid, quantity: 1 },
                    success: function(res) {
                        if(res.success) {
                            successCount++;
                            $('.cart-item-count').text(res.data.qty);
                        } else {
                            failCount++;
                        }
                        resolve();
                    },
                    error: function() {
                        failCount++;
                        resolve();
                    }
                });
            });
        });

        Promise.all(requests).then(() => {
            $.LoadingOverlay('hide');
            btn.prop('disabled', false).html('<i class="fa fa-shopping-cart"></i> Thêm tất cả nguyên liệu vào giỏ');
            
            if(successCount > 0) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Đã thêm ' + successCount + ' nguyên liệu vào giỏ hàng.',
                    showConfirmButton: true
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Thất bại',
                    text: 'Không thể thêm sản phẩm vào giỏ hàng. Vui lòng đăng nhập hoặc thử lại.'
                });
            }
        });
    });
});
</script>
@endsection
