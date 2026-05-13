<div class="row" id="product-grid-container">
    @if(count($listProduct) > 0)
        @foreach($listProduct as $product)
            @include('web.include.item_product_search')
        @endforeach
    @else
        <div class="col-12 text-center py-20 bg-gray-50 rounded-[2.5rem]">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                <i class="fa fa-search text-3xl text-gray-200"></i>
            </div>
            <h3 class="text-xl font-black text-gray-900 mb-2">Không tìm thấy sản phẩm</h3>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest">Chúng tôi không tìm thấy kết quả nào phù hợp với bộ lọc hiện tại</p>
            <button type="button" onclick="clearAllFilters()" class="inline-block mt-8 bg-green-900 text-white px-8 py-3 rounded-full font-bold text-xs tracking-widest hover:bg-black transition-all">XÓA BỘ LỌC</button>
        </div>
    @endif
</div>

<div class="row mt-10">
    <div class="col-12" id="pagination-container">
        {{ $listProduct->appends(request()->input())->links() }}
    </div>
</div>
