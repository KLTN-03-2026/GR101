<div class="col-lg-3 col-md-4 col-sm-6 mb-8 group">
    <!-- Modern Product Card -->
    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500 flex flex-col h-full overflow-hidden group-hover:-translate-y-2">
        
        <!-- Product Image Section -->
        <div class="relative aspect-square overflow-hidden bg-gray-50/50 p-6 flex items-center justify-center">
            <a href="{{ route('web.detail', $product->id) }}" class="w-full h-full block">
                <img src="{{ $product->getImage() }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-contain transition-transform duration-700 group-hover:scale-110">
            </a>
            
            <!-- Quick Action / Badge -->
            <div class="absolute top-4 left-4">
                <span class="bg-white/90 backdrop-blur-sm text-green-800 text-[10px] font-black px-3 py-1 rounded-full shadow-sm border border-green-100 uppercase tracking-tighter">
                    {{ $product->Category->name ?? 'Sản phẩm' }}
                </span>
            </div>
        </div>

        <!-- Product Details Section -->
        <div class="p-6 flex flex-col flex-1 space-y-4">
            <!-- Product Name -->
            <h3 class="font-bold text-gray-900 text-lg leading-snug h-14 overflow-hidden group-hover:text-primary-green transition-colors">
                <a href="{{ route('web.detail', $product->id) }}">
                    {{ $product->name }}
                </a>
            </h3>

            <!-- Price Area -->
            <div class="flex items-baseline gap-1.5">
                <span class="text-2xl font-black text-gray-900 leading-none">
                    {{ number_format($product->getPrice(), 0, ',', '.') }}
                </span>
                <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">
                    VNĐ @if($product->unit) <span class="text-gray-300 mx-0.5">/</span> {{ $product->unit }} @endif
                </span>
            </div>

            <!-- Promotion / Metadata (Optional) -->
            <div class="flex items-center gap-2 text-[11px] font-bold text-orange-500 uppercase">
                <i class="fa fa-certificate"></i>
                <span>Tươi Ngon Mỗi Ngày</span>
            </div>

            <!-- Quick Actions -->
            <div class="pt-2 flex gap-2">
                <button type="button" 
                        onclick="quickAddToCart(event, '{{ $product->id }}')"
                        class="w-14 h-14 bg-soft-green text-green-900 rounded-2xl flex items-center justify-center hover:bg-green-900 hover:text-white transition-all shadow-md active:scale-90 group/btn">
                    <i class="fa fa-cart-plus text-lg group-hover/btn:scale-110 transition-transform"></i>
                </button>
                <a href="{{ route('web.detail', $product->id) }}" 
                   class="flex-1 bg-gray-900 text-white py-4 rounded-2xl font-black text-[11px] tracking-widest hover:bg-green-900 transition-all shadow-md active:scale-95 flex items-center justify-center gap-2 uppercase">
                    Chi tiết
                    <i class="fa fa-chevron-right text-[8px]"></i>
                </a>
            </div>
        </div>
    </div>
</div>
