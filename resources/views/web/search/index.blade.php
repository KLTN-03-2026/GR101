@extends('layouts.master_user')
@section('content')
    <style>
        /* Make the image fully responsive */
        .carousel-inner img {
            width: 100%;
            height: 100%;
        }
    </style>
    <section class="product-area pt-60 pb-45 font-sans">
        <div class="container">
            <div class="row">
                <!-- Sidebar -->
            <div class="col-lg-3">
                @include('web.include.attr_search', ['listProductId' => $listProductId])
            </div>

                <!-- Main Content -->
                <div class="col-lg-9 relative">
                    <div class="mb-10 flex items-center justify-between px-4">
                        <div class="space-y-1">
                            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Kết quả tìm kiếm</h2>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tìm thấy <span id="total-products">{{ $listProduct->total() }}</span> sản phẩm cho "{{ $search }}"</p>
                        </div>
                        <div class="h-px flex-1 bg-gray-100 mx-10 hidden md:block"></div>
                    </div>

                    <!-- AJAX Loading Overlay -->
                    <div id="filter-loader" class="absolute inset-0 bg-white/50 backdrop-blur-[2px] z-50 flex items-center justify-center hidden">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-12 h-12 border-4 border-green-900/10 border-t-green-900 rounded-full animate-spin"></div>
                            <p class="text-[10px] font-black text-green-900 uppercase tracking-[0.2em]">Đang lọc...</p>
                        </div>
                    </div>

                    <div id="ajax-product-container">
                        @include('web.include.product_list_partial')
                    </div>
                </div>
        </div>
    </section>
@endsection
