<div class="group h-full flex flex-col bg-white rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden relative">
    <!-- Image Area -->
    <div class="relative aspect-square overflow-hidden bg-gray-50/50 p-6 flex items-center justify-center">
        <a href="{{ route('web.detail', $product->id) }}" class="w-full h-full">
            <img src="{{ $product->getImage() }}" 
                 alt="{{ $product->name }}" 
                 class="w-full h-full object-contain transform group-hover:scale-110 transition-transform duration-700">
        </a>
        
        <!-- Sticker -->
        <div class="absolute top-4 left-4 z-10">
            <span class="bg-green-600 text-white text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full shadow-lg">Mới</span>
        </div>

        <!-- Quick View Overlay (Subtle) -->
        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
    </div>

    <!-- Info Area -->
    <div class="p-6 flex flex-col flex-grow">
        <div class="mb-2">
            <a href="{{ route('web.detail.category', $product->Category->id ?? '#') }}" 
               class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] hover:text-green-600 transition-colors">
                {{ $product->Category->name ?? 'Nông sản' }}
            </a>
        </div>
        
        <h4 class="mb-3 flex-grow">
            <a href="{{ route('web.detail', $product->id) }}" 
               class="text-sm font-bold text-gray-900 hover:text-green-700 transition-colors line-clamp-2 leading-snug">
                {{ $product->name }}
            </a>
        </h4>

        <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-50">
            <div class="flex flex-col">
                <span class="text-xs text-gray-400 font-medium mb-0.5 uppercase tracking-tighter">Giá bán</span>
                <span class="text-base font-black text-green-700 tracking-tight">{{ formatVnd($product->getPrice()) }}</span>
            </div>
            
            <div class="flex items-center gap-2">
                <button type="button" 
                        onclick="quickAddToCart(event, '{{ $product->id }}')"
                        class="w-10 h-10 bg-soft-green text-green-700 rounded-xl flex items-center justify-center hover:bg-green-900 hover:text-white transition-all shadow-sm group/btn">
                    <i class="fa fa-cart-plus text-sm group-hover/btn:scale-110 transition-transform"></i>
                </button>
                <a href="{{ route('web.detail', $product->id) }}" 
                   class="w-10 h-10 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center hover:bg-gray-900 hover:text-white hover:rotate-[360deg] transition-all duration-700 shadow-sm">
                    <i class="fa fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    </div>
</div>
