@extends('layouts.master_user')

@section('content')
<div class="bg-white min-h-screen font-sans">
    <!-- Breadcrumb -->
    <div class="bg-gray-50/50 border-b border-gray-100 py-4">
        <div class="container mx-auto px-4 md:px-10">
            <nav class="flex text-xs font-bold text-gray-400 uppercase tracking-widest gap-3 items-center">
                <a href="{{ route('web.index') }}" class="hover:text-green-900 transition-colors">Trang chủ</a>
                <i class="fa fa-chevron-right text-[8px]"></i>
                <span class="text-green-900">Chi tiết sản phẩm</span>
            </nav>
        </div>
    </div>

    <!-- Product Detail Content -->
    <main class="py-12 lg:py-20">
        <div class="container mx-auto px-4 md:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-24">
                <!-- Left: Image Gallery -->
                <div class="lg:col-span-6 space-y-8">
                    <div class="relative group bg-gray-50 rounded-[3rem] overflow-hidden border border-gray-100 shadow-sm transition-all duration-700">
                        <!-- Zoom Effect Container -->
                        <div class="aspect-square flex items-center justify-center p-12 overflow-hidden cursor-zoom-in" id="image-zoom-container">
                            <img src="{{ $product->getImage() }}" alt="{{ $product->name }}" 
                                 class="w-full h-full object-contain transition-transform duration-500 origin-center" 
                                 id="main-image">
                        </div>
                        
                        <!-- Sale Badge if any -->
                        <div class="absolute top-8 left-8 bg-orange-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest shadow-lg">
                            HOT DEAL
                        </div>
                    </div>
                    
                    @if(!empty($listImage) || !empty($product->listProductChild))
                        <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide px-2">
                            <div class="w-24 h-24 flex-shrink-0 bg-white border-2 border-green-900 rounded-3xl overflow-hidden cursor-pointer shadow-md transform hover:scale-105 transition-all p-2 thumb-container active">
                                <img src="{{ $product->getImage() }}" class="w-full h-full object-contain thumb-image" onclick="changeImage(this.src, this)">
                            </div>
                            @if(!empty($listImage))
                                @foreach($listImage as $image)
                                    <div class="w-24 h-24 flex-shrink-0 bg-white border-2 border-transparent rounded-3xl overflow-hidden cursor-pointer hover:border-green-200 transition-all p-2 thumb-container">
                                        <img src="{{ $image->getImage() }}" class="w-full h-full object-contain thumb-image" onclick="changeImage(this.src, this)">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Right: Product Info -->
                <div class="lg:col-span-6 flex flex-col pt-4">
                    <div class="space-y-6 mb-10">
                        <div class="flex items-center gap-3">
                            <span class="inline-block px-4 py-1.5 bg-soft-green text-green-700 text-[10px] font-black uppercase tracking-[0.2em] rounded-full">Sản phẩm Organic</span>
                            @if($product->getQuantityActive() > 0)
                                <span class="text-[10px] font-black text-green-600 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Còn hàng
                                </span>
                            @else
                                <span class="text-[10px] font-black text-red-500 uppercase tracking-widest flex items-center gap-2">
                                    <span class="w-2 h-2 bg-red-500 rounded-full"></span> Hết hàng
                                </span>
                            @endif
                        </div>
                        
                        <h1 class="text-4xl md:text-6xl font-black text-gray-900 leading-[1.1] tracking-tight">{{ $product->name }}</h1>
                        
                        <div class="flex items-center gap-8">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Giá ưu đãi</span>
                                <span class="text-5xl font-black text-green-900 tracking-tighter" id="product-price">{{ formatVnd($product->price) }}</span>
                                @if($product->unit)
                                    <span class="text-xl font-bold text-gray-400 ml-2">/ {{ $product->unit }}</span>
                                @endif
                            </div>
                            <div class="h-12 w-px bg-gray-100"></div>
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Đánh giá</span>
                                <div class="flex items-center gap-2">
                                    <div class="flex text-yellow-400 text-sm">
                                        <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                                    </div>
                                    <span class="text-sm font-black text-gray-900">({{ count($product->reviews) }})</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="space-y-10 bg-gray-50/80 p-10 rounded-[3rem] border border-gray-100 shadow-inner mb-10">
                        @if(!empty($product->listProductChild) && $product->listProductChild->count() > 0)
                            <div class="space-y-4">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] block">Lựa chọn phiên bản</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    @foreach($product->listProductChild as $productChild)
                                        <button type="button" 
                                                class="variant-btn flex flex-col items-center justify-center p-5 bg-white border-2 border-transparent rounded-[1.5rem] hover:border-green-300 transition-all group {{ $loop->first ? 'active border-green-900 shadow-xl scale-105' : '' }}"
                                                onclick="selectVariant(this, '{{ $productChild->id }}', '{{ formatVnd($productChild->price) }}', '{{ $productChild->quantity }}', '{{ $productChild->getImage() }}')">
                                            <span class="text-sm font-black text-gray-900 group-hover:text-green-900 transition-colors">{{ $productChild->attributeTitle() }}</span>
                                            <span class="text-[10px] text-gray-400 font-bold mt-1 uppercase tracking-tighter">{{ formatVnd($productChild->price) }}</span>
                                        </button>
                                    @endforeach
                                    <input type="hidden" id="product-child-id" value="{{ $product->listProductChild->first()->id ?? '' }}">
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col sm:flex-row items-end gap-6">
                            <div class="space-y-4 w-full sm:w-auto">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] block">Số lượng mua</label>
                                <div class="flex items-center bg-white border border-gray-200 p-2 rounded-full shadow-sm">
                                    <button type="button" class="w-12 h-12 flex items-center justify-center bg-gray-50 text-gray-900 rounded-full hover:bg-red-500 hover:text-white transition-all active:scale-90" onclick="updateQty(-1)">
                                        <i class="fa fa-minus text-xs"></i>
                                    </button>
                                    <input type="number" min="1" id="quantity" value="1" class="w-20 text-center bg-transparent border-none outline-none font-black text-xl text-gray-900">
                                    <button type="button" class="w-12 h-12 flex items-center justify-center bg-gray-50 text-gray-900 rounded-full hover:bg-green-900 hover:text-white transition-all active:scale-90" onclick="updateQty(1)">
                                        <i class="fa fa-plus text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex-1 w-full">
                                <button type="button" 
                                        @if($product->getQuantityActive() <= 0) disabled @endif 
                                        class="add-to-cart w-full bg-green-900 text-white py-5 rounded-full font-black text-xs tracking-[0.2em] shadow-2xl hover:bg-black transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-4 disabled:bg-gray-300 disabled:shadow-none disabled:transform-none">
                                    <i class="fa fa-shopping-basket text-base"></i>
                                    MUA NGAY - GIAO TẬN NƠI
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Badges -->
                    <div class="grid grid-cols-3 gap-6">
                        <div class="flex flex-col items-center gap-3 text-center">
                            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-green-800">
                                <i class="fa fa-leaf"></i>
                            </div>
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">100% Hữu cơ</span>
                        </div>
                        <div class="flex flex-col items-center gap-3 text-center">
                            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-green-800">
                                <i class="fa fa-shield-check"></i>
                            </div>
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">An toàn VietGAP</span>
                        </div>
                        <div class="flex flex-col items-center gap-3 text-center">
                            <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center text-green-800">
                                <i class="fa fa-history"></i>
                            </div>
                            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Đổi trả 24h</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Section -->
            <div class="mt-32">
                <div class="flex justify-center border-b border-gray-100 gap-16 mb-20 overflow-x-auto scrollbar-hide">
                    <button onclick="switchTab('description')" id="tab-desc-btn" class="tab-btn active text-xs font-black text-green-900 border-b-2 border-green-900 pb-6 uppercase tracking-[0.3em] transition-all">Thông tin chi tiết</button>
                    <button onclick="switchTab('reviews')" id="tab-reviews-btn" class="tab-btn text-xs font-bold text-gray-400 hover:text-green-900 pb-6 uppercase tracking-[0.3em] transition-all">Đánh giá khách hàng ({{ count($product->reviews) }})</button>
                </div>

                <!-- Description Tab -->
                <div id="tab-description" class="tab-content transition-all duration-700">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-20">
                        <div class="lg:col-span-8">
                            <div class="prose prose-lg text-gray-600 leading-relaxed max-w-none font-medium">
                                {!! nl2br($product->description) !!}
                            </div>
                        </div>
                        <div class="lg:col-span-4">
                            <div class="bg-gray-50 p-10 rounded-[3rem] border border-gray-100 sticky top-32">
                                <h3 class="text-lg font-black text-gray-900 mb-8 flex items-center gap-4">
                                    <span class="w-2 h-8 bg-green-900 rounded-full"></span>
                                    Thông số kỹ thuật
                                </h3>
                                <div class="space-y-6">
                                    @php
                                        $specs = [
                                            ['label' => 'Thương hiệu', 'value' => 'Nông Sản Xanh', 'icon' => 'fa-tag'],
                                            ['label' => 'Xuất xứ', 'value' => 'Việt Nam', 'icon' => 'fa-map-marker-alt'],
                                            ['label' => 'Hạn sử dụng', 'value' => '3-5 ngày', 'icon' => 'fa-calendar-alt'],
                                            ['label' => 'Bảo quản', 'value' => 'Ngăn mát tủ lạnh', 'icon' => 'fa-snowflake']
                                        ];
                                    @endphp
                                    @foreach($specs as $spec)
                                        <div class="flex justify-between items-center border-b border-gray-200/50 pb-4">
                                            <div class="flex items-center gap-3">
                                                <i class="fa {{ $spec['icon'] }} text-gray-300 text-[10px]"></i>
                                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $spec['label'] }}</span>
                                            </div>
                                            <span class="text-sm font-black text-gray-900">{{ $spec['value'] }}</span>
                                        </div>
                                    @endforeach
                                    
                                    @if(!empty($product->listAttribute))
                                        @foreach($product->listAttribute as $attr)
                                            @if(!empty($attr->pivot->text_value))
                                                <div class="flex justify-between items-center border-b border-gray-200/50 pb-4">
                                                    <div class="flex items-center gap-3">
                                                        <i class="fa fa-info-circle text-gray-300 text-[10px]"></i>
                                                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $attr->name }}</span>
                                                    </div>
                                                    <span class="text-sm font-black text-gray-900">{{ $attr->pivot->text_value }}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div id="tab-reviews" class="tab-content hidden opacity-0 transition-all duration-700 translate-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-20">
                        <!-- Review Distribution -->
                        <div class="lg:col-span-4">
                            <div class="bg-white p-12 rounded-[3rem] border border-gray-100 shadow-sm sticky top-32">
                                <h3 class="text-4xl font-black text-gray-900 mb-2">4.9</h3>
                                <div class="flex text-yellow-400 gap-1 mb-4">
                                    <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                                </div>
                                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-10">Dựa trên {{ count($product->reviews) }} đánh giá</p>
                                
                                <div class="space-y-4">
                                    @for($i = 5; $i >= 1; $i--)
                                        <div class="flex items-center gap-4">
                                            <span class="text-[10px] font-black text-gray-400 w-4">{{ $i }}</span>
                                            <div class="flex-1 h-1.5 bg-gray-50 rounded-full overflow-hidden">
                                                <div class="h-full bg-yellow-400 rounded-full" style="width: {{ $i == 5 ? '90%' : ($i == 4 ? '8%' : '2%') }}"></div>
                                            </div>
                                            <span class="text-[10px] font-bold text-gray-300">{{ $i == 5 ? '90%' : ($i == 4 ? '8%' : '2%') }}</span>
                                        </div>
                                    @endfor
                                </div>

                                <button type="button" onclick="document.getElementById('review-form-container').scrollIntoView({behavior: 'smooth'})" class="w-full mt-12 border-2 border-gray-100 text-gray-900 py-4 rounded-full font-black text-[10px] tracking-widest uppercase hover:bg-gray-900 hover:text-white transition-all">
                                    Viết đánh giá của bạn
                                </button>
                            </div>
                        </div>

                        <!-- Review List -->
                        <div class="lg:col-span-8 space-y-10">
                            @if(count($product->reviews) > 0)
                                @foreach($product->reviews as $review)
                                    <div class="flex gap-8 group">
                                        <div class="w-16 h-16 bg-soft-green rounded-[1.25rem] flex items-center justify-center flex-shrink-0 text-green-900 font-black text-2xl shadow-sm">
                                            {{ substr($review->User->name ?? 'K', 0, 1) }}
                                        </div>
                                        <div class="flex-1 space-y-3">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <h4 class="font-black text-gray-900 tracking-tight">{{ $review->User->name ?? 'Khách hàng ẩn danh' }}</h4>
                                                    <div class="flex text-yellow-400 text-[10px] gap-1 mt-1">
                                                        @for($star = 1; $star <= 5; $star++)
                                                            <i class="fa fa-star {{ $star <= $review->rating ? '' : 'text-gray-100' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-base text-gray-600 leading-relaxed font-medium">
                                                {{ $review->comment }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <!-- Review Form -->
                            <div id="review-form-container" class="pt-12 mt-12 border-t border-gray-50">
                                <h3 class="text-xl font-black text-gray-900 mb-8 uppercase tracking-widest">Gửi đánh giá mới</h3>
                                <form id="review-form" class="space-y-8 bg-gray-50 p-10 rounded-[3rem]">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Bạn chấm mấy sao?</label>
                                        <div class="flex gap-3">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" onclick="setRating({{ $i }})" class="star-btn text-3xl text-gray-200 hover:text-yellow-400 transition-all hover:scale-125" data-rating="{{ $i }}">
                                                    <i class="fa fa-star"></i>
                                                </button>
                                            @endfor
                                            <input type="hidden" name="rating" id="rating-input" value="5">
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Cảm nhận của bạn</label>
                                        <textarea name="comment" rows="5" placeholder="Chia sẻ trải nghiệm của bạn về chất lượng sản phẩm..." 
                                                  class="w-full bg-white border-none rounded-[2rem] p-8 focus:ring-2 focus:ring-green-900/10 transition-all outline-none text-base font-medium shadow-sm"></textarea>
                                    </div>
                                    <button type="submit" class="bg-green-900 text-white px-12 py-5 rounded-full font-black text-xs tracking-widest shadow-2xl hover:bg-black transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-4">
                                        ĐĂNG ĐÁNH GIÁ <i class="fa fa-paper-plane text-[10px]"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Sticky Buy Bar -->
    <div id="sticky-buy-bar" class="fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-xl border-t border-gray-100 py-4 px-6 z-50 transform translate-y-full transition-transform duration-500 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] md:py-6">
        <div class="container mx-auto flex items-center justify-between gap-6">
            <div class="hidden md:flex items-center gap-5">
                <div class="w-16 h-16 bg-gray-50 rounded-2xl p-2 border border-gray-100">
                    <img src="{{ $product->getImage() }}" class="w-full h-full object-contain">
                </div>
                <div>
                    <h4 class="text-sm font-black text-gray-900 tracking-tight">{{ $product->name }}</h4>
                    <span class="text-xs font-bold text-green-900">{{ formatVnd($product->price) }} @if($product->unit) / {{ $product->unit }} @endif</span>
                </div>
            </div>
            <div class="flex-1 md:flex-none flex items-center gap-4">
                <div class="flex items-center bg-gray-50 p-1 rounded-full border border-gray-100">
                    <button type="button" class="w-10 h-10 flex items-center justify-center text-gray-900 rounded-full hover:bg-white transition-all" onclick="updateQty(-1)">
                        <i class="fa fa-minus text-[8px]"></i>
                    </button>
                    <input type="number" min="1" id="sticky-qty" value="1" class="w-12 text-center bg-transparent border-none font-black text-gray-900 text-sm" >
                    <button type="button" class="w-10 h-10 flex items-center justify-center text-gray-900 rounded-full hover:bg-white transition-all" onclick="updateQty(1)">
                        <i class="fa fa-plus text-[8px]"></i>
                    </button>
                </div>
                <button type="button" onclick="$('.add-to-cart').first().click()" class="flex-1 md:flex-none bg-green-900 text-white px-10 py-4 rounded-full font-black text-[10px] tracking-widest uppercase shadow-xl hover:bg-black transition-all whitespace-nowrap">
                    MUA NGAY
                </button>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <section class="py-32 bg-gray-50/50">
        <div class="container mx-auto px-4 md:px-10">
            <div class="flex items-center justify-between mb-16">
                <h2 class="text-4xl font-black text-gray-900 flex items-center gap-6">
                    <span class="w-3 h-12 bg-green-900 rounded-full"></span>
                    Sản phẩm tương tự
                </h2>
                <a href="{{ route('web.index') }}" class="text-xs font-black text-gray-400 hover:text-green-900 transition-colors uppercase tracking-[0.3em] flex items-center gap-3">
                    XEM TẤT CẢ <i class="fa fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-10">
                @foreach($product->getListProductSameCategory() as $productItem)
                    @include('web.include.item_product', ['product' => $productItem])
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
<script>
    // Image Zoom Functionality
    const mainImg = document.getElementById('main-image');
    const zoomContainer = document.getElementById('image-zoom-container');

    zoomContainer.addEventListener('mousemove', (e) => {
        const { left, top, width, height } = zoomContainer.getBoundingClientRect();
        const x = ((e.clientX - left) / width) * 100;
        const y = ((e.clientY - top) / height) * 100;
        
        mainImg.style.transformOrigin = `${x}% ${y}%`;
        mainImg.style.transform = 'scale(2)';
    });

    zoomContainer.addEventListener('mouseleave', () => {
        mainImg.style.transform = 'scale(1)';
        mainImg.style.transformOrigin = 'center';
    });

    function changeImage(src, element) {
        mainImg.src = src;
        document.querySelectorAll('.thumb-container').forEach(c => {
            c.classList.remove('border-green-900', 'shadow-md', 'scale-105', 'active');
            c.classList.add('border-transparent');
        });
        element.parentElement.classList.remove('border-transparent');
        element.parentElement.classList.add('border-green-900', 'shadow-md', 'scale-105', 'active');
    }

    function selectVariant(element, id, price, quantity, imgSrc) {
        document.querySelectorAll('.variant-btn').forEach(btn => {
            btn.classList.remove('active', 'border-green-900', 'shadow-xl', 'scale-105');
            btn.classList.add('border-transparent');
        });
        element.classList.remove('border-transparent');
        element.classList.add('active', 'border-green-900', 'shadow-xl', 'scale-105');
        
        document.getElementById('product-price').innerText = price;
        document.getElementById('product-child-id').value = id;
        
        const addToCartBtn = $('.add-to-cart');
        if (parseFloat(quantity) <= 0) {
            addToCartBtn.prop('disabled', true).text('TẠM HẾT HÀNG');
        } else {
            addToCartBtn.prop('disabled', false).html('<i class="fa fa-shopping-basket text-base"></i> MUA NGAY - GIAO TẬN NƠI');
        }

        if(imgSrc && imgSrc !== '') {
            changeImage(imgSrc, document.querySelector(`.thumb-image[src="${imgSrc}"]`));
        }
    }

    function updateQty(step) {
        const qtyInputs = [document.getElementById('quantity'), document.getElementById('sticky-qty')];
        let currentQty = parseInt(qtyInputs[0].value);
        if (isNaN(currentQty)) currentQty = 1;
        currentQty += step;
        if (currentQty < 1) currentQty = 1;
        qtyInputs.forEach(input => input.value = currentQty);
    }

    // Sync manual input
    $('#quantity, #sticky-qty').on('change', function() {
        let val = parseInt($(this).val());
        if (isNaN(val) || val < 1) val = 1;
        $('#quantity, #sticky-qty').val(val);
    });

    function switchTab(tabId) {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'text-green-900', 'border-b-2', 'border-green-900', 'font-black');
            btn.classList.add('text-gray-400', 'font-bold');
        });
        
        const activeBtn = document.getElementById(`tab-${tabId === 'description' ? 'desc' : 'reviews'}-btn`);
        activeBtn.classList.add('active', 'text-green-900', 'border-b-2', 'border-green-900', 'font-black');
        activeBtn.classList.remove('text-gray-400', 'font-bold');

        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden', 'opacity-0', 'translate-y-8');
        });
        
        const activeContent = document.getElementById(`tab-${tabId}`);
        activeContent.classList.remove('hidden');
        setTimeout(() => {
            activeContent.classList.remove('opacity-0', 'translate-y-8');
        }, 50);
    }

    function setRating(rating) {
        document.getElementById('rating-input').value = rating;
        document.querySelectorAll('.star-btn').forEach((btn, index) => {
            if (index < rating) {
                btn.classList.add('text-yellow-400');
                btn.classList.remove('text-gray-200');
            } else {
                btn.classList.remove('text-yellow-400');
                btn.classList.add('text-gray-200');
            }
        });
    }

    // Sticky Buy Bar Logic
    window.onscroll = function() {
        const buyBtn = document.querySelector('.add-to-cart');
        const stickyBar = document.getElementById('sticky-buy-bar');
        const buyBtnPosition = buyBtn.getBoundingClientRect().bottom;

        if (buyBtnPosition < 0) {
            stickyBar.classList.remove('translate-y-full');
        } else {
            stickyBar.classList.add('translate-y-full');
        }
    };

    $(document).ready(function () {
        setRating(5);

        $('#review-form').submit(function (e) {
            e.preventDefault();
            $.LoadingOverlay('show');
            $.ajax({
                url: '{{ route("web.product.review") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    $.LoadingOverlay('hide');
                    if (res.success) {
                        Swal.fire({ icon: 'success', title: 'Thành công', text: res.message }).then(() => location.reload());
                    }
                },
                error: function (xhr) {
                    $.LoadingOverlay('hide');
                    let msg = xhr.status === 401 ? 'Bạn cần đăng nhập để đánh giá.' : 'Có lỗi xảy ra.';
                    Swal.fire({ icon: 'error', title: 'Lỗi', text: msg });
                }
            });
        });

        $('.add-to-cart').click(function (){
            let productId = @json($product->id);
            const variantId = $('#product-child-id').val();
            if(variantId) productId = variantId;

            $.LoadingOverlay('show');
            $.ajax({
                data: { product_id: productId, quantity: $('#quantity').val() },
                method: 'get',
                url: '{{ route("web.cart.add") }}',
                success: function(data){
                    $.LoadingOverlay('hide');
                    if (data.success) {
                        $('.cart-item-count').text(data.data.qty);
                        Swal.fire({ icon: 'success', title: 'Đã thêm!', text: 'Sản phẩm đã nằm trong giỏ hàng của bạn.', showConfirmButton: false, timer: 1500 });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Lỗi', text: data.message || data.data.message });
                    }
                }
            });
        });
    });
</script>
<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    #image-zoom-container:hover #main-image { cursor: zoom-in; }
</style>
@endsection
