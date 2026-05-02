<div class="col-3 mt-40">
    <!-- single-product-wrap start -->
    <div class="single-product-wrap">
        <div class="product-image">
            <a href="{{ route('web.detail', $product->id) }}">
                <img src="{{ $product->getImage() }}" alt="Li's Product Image">
            </a>
        </div>
       <div class="product_desc" style="padding: 12px; background: #fff; border-radius: 0 0 12px 12px; font-family: 'SF Pro Display', -apple-system, system-ui, sans-serif;">
    
    <div class="product_desc_info">
        <div class="product-review" style="margin-bottom: 8px;">
            <span style="display: inline-block; padding: 2px 8px; border: 1px solid #d1d5db; border-radius: 4px; background: #f9fafb;">
                <h5 class="manufacturer" style="margin: 0; line-height: 1;">
                    <a href="#" style="font-size: 12px; color: #374151; text-decoration: none; font-weight: 500;">
                        {{ $product->Category->name ?? 'Sản phẩm' }}
                    </a>
                </h5>
            </span>
        </div>

        <h4 style="margin: 0 0 12px 0;">
            <a class="product_name" href="{{ route('web.detail', $product->id) }}" 
               style="font-size: 17px; color: #4b5563; text-decoration: none; font-weight: 400; line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 44px;">
                {{ $product->name }}
            </a>
        </h4>

        <div class="price-box" style="margin-bottom: 15px;">
            <div style="display: flex; align-items: baseline; color: #c2410c;">
                <span class="new-price" style="font-size: 22px; font-weight: 700;">
                    {{ number_format($product->getPrice(), 0, ',', '.') }}
                </span>
                <span style="font-size: 16px; font-weight: 700; margin-left: 2px;">VNĐ</span>
            </div>
            <div style="font-size: 13px; color: #9ca3af; text-decoration: line-through; margin-top: -2px;">
                ({{ number_format($product->getPrice() * 1.1, 0, ',', '.') }}đ/kg)
            </div>
        </div>
        
        <div style="font-size: 13px; color: #f59e0b; font-weight: 600; text-transform: uppercase; margin-bottom: 10px;">
            TẶNG GIA VỊ (Mua từ 0.6kg)
        </div>
    </div>

    <div class="add-actions" style="margin: 0 -12px -12px -12px;">
        <a href="{{ route('web.detail', $product->id) }}" 
           style="display: block; width: 100%; background: #f0fdf4; color: #166534; text-align: center; padding: 12px 0; border-radius: 0 0 12px 12px; font-weight: 600; font-size: 16px; text-decoration: none; transition: 0.3s; border-top: 1px solid #dcfce7;">
            MUA
        </a>
    </div>
</div>
    </div>
    <!-- single-product-wrap end -->
</div>
