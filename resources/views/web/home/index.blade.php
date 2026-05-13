@extends('layouts.master_user')
@section('content')
    <div class="slider-with-banner">
        <div class="container">
            <div class="row">
                <!-- Begin Modern Category Menu Area -->
                <div class="col-lg-3">
                    <aside class="bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm sticky top-24">
                        <div class="p-4 border-b border-gray-50 flex items-center gap-3 bg-gray-50/50">
                            <i class="fa fa-th-large text-green-600"></i>
                            <h2 class="font-bold text-gray-900 uppercase tracking-wider text-xs">Danh Mục</h2>
                        </div>
                        <ul class="divide-y divide-gray-50">
                            @foreach($listCategory as $category)
                                <li>
                                    <a href="{{ route('web.detail.category', $category->id) }}" 
                                       class="w-full text-left px-5 py-3.5 hover:bg-soft-green transition-all flex items-center gap-3 group text-sm font-medium text-gray-700 hover:text-green-700">
                                        <i class="fa fa-chevron-right text-[10px] text-gray-300 group-hover:text-green-500 transition-colors"></i>
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                            <li>
                                <button class="w-full text-left px-5 py-3.5 flex items-center gap-3 hover:bg-gray-50 text-gray-500 transition-colors text-xs font-bold italic">
                                    <i class="fa fa-plus"></i>
                                    XEM THÊM
                                </button>
                            </li>
                        </ul>
                    </aside>
                </div>
                <!-- Category Menu Area End Here -->
                <!-- Begin Modern Slider Area -->
                <div class="col-lg-9">
                        <div id="main-slider" class="carousel slide carousel-fade" data-ride="carousel" data-interval="5000">
                            <!-- Indicators -->
                            <ul class="carousel-indicators mb-8">
                                <li data-target="#main-slider" data-slide-to="0" class="active w-12 h-1.5 rounded-full transition-all duration-500"></li>
                                <li data-target="#main-slider" data-slide-to="1" class="w-12 h-1.5 rounded-full transition-all duration-500"></li>
                                <li data-target="#main-slider" data-slide-to="2" class="w-12 h-1.5 rounded-full transition-all duration-500"></li>
                            </ul>

                            <!-- The slideshow -->
                            <div class="carousel-inner">
                                <!-- Slide 1: Dynamic from Database -->
                                @foreach($listBanner as $key => $banner)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <div class="relative h-[480px] overflow-hidden">
                                            @php
                                                $bimg = $banner->getImage();
                                            @endphp
                                            <img src="{{ $bimg }}" alt="Banner 1" class="w-full h-full object-cover transition-transform duration-[3000ms] group-hover:scale-110">
                                            <div class="absolute inset-0 bg-black/10 transition-opacity group-hover:bg-black/20"></div>
                                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-10">
                                                <div class="transform translate-y-10 opacity-0 animate-in fade-in slide-in-from-bottom-10 duration-1000 fill-mode-forwards">
                                                    <a href="#" class="inline-block bg-white text-green-900 px-12 py-4 rounded-full font-black text-sm tracking-[0.2em] shadow-2xl hover:bg-green-900 hover:text-white transition-all transform hover:scale-110 active:scale-95 uppercase border-4 border-white/20">
                                                        Shop Now
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Slide 2: High Quality Placeholder -->
                                <div class="carousel-item {{ count($listBanner) == 0 ? 'active' : '' }}">
                                    <div class="relative h-[480px] overflow-hidden">
                                        <img src="https://images.unsplash.com/photo-1540148426945-6cf22a6b2383?auto=format&fit=crop&q=80&w=1500" alt="Fresh Vegetables" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent"></div>
                                        <div class="absolute inset-0 flex flex-col items-start justify-center p-20">
                                            <span class="text-yellow-400 font-black tracking-[0.3em] uppercase text-xs mb-4">Gimi Organic</span>
                                            <h2 class="text-5xl font-black text-white mb-8 leading-tight max-w-md">Rau Củ Tươi Sạch <br/> Mỗi Ngày</h2>
                                            <a href="#" class="inline-block bg-white text-green-900 px-12 py-4 rounded-full font-black text-sm tracking-[0.2em] shadow-2xl hover:!bg-green-900 hover:!text-white transition-all transform hover:scale-110 active:scale-95 uppercase border-4 border-white/20">
                                                Khám Phá Ngay
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Slide 3: High Quality Placeholder -->
                                <div class="carousel-item">
                                    <div class="relative h-[480px] overflow-hidden">
                                        <img src="https://images.unsplash.com/photo-1610832958506-aa56368176cf?auto=format&fit=crop&q=80&w=1500" alt="Healthy Fruits" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-l from-black/70 to-transparent"></div>
                                        <div class="absolute inset-0 flex flex-col items-end justify-center p-20 text-right">
                                            <span class="text-green-400 font-black tracking-[0.3em] uppercase text-xs mb-4">100% Tự Nhiên</span>
                                            <h2 class="text-5xl font-black text-white mb-8 leading-tight max-w-md">Trái Cây Nhiệt Đới <br/> Chuẩn VietGap</h2>
                                            <a href="#" class="inline-block bg-white text-green-900 px-12 py-4 rounded-full font-black text-sm tracking-[0.2em] shadow-2xl hover:!bg-green-900 hover:!text-white transition-all transform hover:scale-110 active:scale-95 uppercase border-4 border-white/20">
                                                Mua Ngay
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Controls -->
                            <button class="carousel-control-prev w-20 opacity-0 group-hover:opacity-100 transition-all duration-500 z-30" data-target="#main-slider" data-slide="prev">
                                <div class="bg-white/10 backdrop-blur-md w-14 h-14 rounded-full flex items-center justify-center border border-white/20 hover:bg-white hover:text-green-900 transition-all shadow-xl">
                                    <i class="fa fa-chevron-left text-xl"></i>
                                </div>
                            </button>
                            <button class="carousel-control-next w-20 opacity-0 group-hover:opacity-100 transition-all duration-500 z-30" data-target="#main-slider" data-slide="next">
                                <div class="bg-white/10 backdrop-blur-md w-14 h-14 rounded-full flex items-center justify-center border border-white/20 hover:bg-white hover:text-green-900 transition-all shadow-xl">
                                    <i class="fa fa-chevron-right text-xl"></i>
                                </div>
                            </button>
                        </div>
                </div>
                <!-- Slider Area End Here -->
            </div>
        </div>
    </div>

    <section class="product-area li-laptop-product Special-product pt-60 pb-45">
        <div class="container">
            <div class="row">
                <!-- Begin Li's Section Area -->
                <div class="col-lg-12">
                    <div class="flex items-center justify-between mb-10">
                        <h2 class="text-3xl font-black text-gray-900 flex items-center gap-3">
                            <span class="w-2 h-10 bg-primary-green rounded-full"></span>
                            Sản Phẩm Nổi Bật
                        </h2>
                        <a href="#" class="text-sm font-bold text-green-700 hover:text-green-800 transition-colors">Xem tất cả <i class="fa fa-arrow-right ml-1"></i></a>
                    </div>
                    <div class="row">
                        @foreach($listProduct as $product)
                            @include('web.include.item_product_search')
                        @endforeach
                        <div style="margin-top: 100px;">{{ $listProduct->appends(request()->input())->links() }}</div>
                    </div>
                </div>
                <!-- Li's Section Area End Here -->
            </div>
        </div>
    </section>
    <!-- Modern Knowledge Section -->
    <section class="max-w-7xl mx-auto px-4 md:px-8 py-20 space-y-20 font-sans">
        <!-- Intro Section -->
        <div class="text-center space-y-6">
            <h2 class="text-4xl font-black text-green-900 tracking-tight">Kiến Thức Về Củ & Quả</h2>
            <p class="text-gray-500 max-w-2xl mx-auto leading-relaxed">
                Khám phá những giá trị dinh dưỡng tuyệt vời và cách lựa chọn thực phẩm tươi sạch nhất cho gia đình bạn mỗi ngày.
            </p>
            <div class="flex justify-center gap-4 pt-4">
                <!-- Card Củ -->
                <div class="relative bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100 flex-1 max-w-md group hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                    <!-- Background Image on Hover -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 z-0">
                        <img src="https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?auto=format&fit=crop&q=80&w=800" alt="Roots" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-1000">
                        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]"></div>
                    </div>

                    <div class="relative z-10">
                        <div class="mb-6">
                            <i class="fa fa-seedling text-green-600 text-4xl group-hover:text-white transition-colors duration-500"></i>
                        </div>
                        <h3 class="text-3xl font-black text-gray-900 mb-5 group-hover:text-white transition-colors">Củ là gì?</h3>
                        <p class="text-gray-500 text-base leading-relaxed group-hover:text-white/90 transition-colors">
                            Củ là phần phình to của rễ hoặc thân cây, có chức năng dự trữ chất dinh dưỡng. Ví dụ như khoai lang, cà rốt, khoai tây, củ cải...
                        </p>
                    </div>
                </div>

                <!-- Card Quả -->
                <div class="relative bg-white p-10 rounded-[2.5rem] shadow-sm border border-gray-100 flex-1 max-w-md group hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 overflow-hidden">
                    <!-- Background Image on Hover -->
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-700 z-0">
                        <img src="https://khoinguonsangtao.vn/wp-content/uploads/2022/10/hinh-anh-qua-tao.jpg" alt="Fruits" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-1000">
                        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px]"></div>
                    </div>

                    <div class="relative z-10">
                        <div class="mb-6">
                            <i class="fa fa-apple-alt text-green-600 text-4xl group-hover:text-white transition-colors duration-500"></i>
                        </div>
                        <h3 class="text-3xl font-black text-gray-900 mb-5 group-hover:text-white transition-colors">Quả là gì?</h3>
                        <p class="text-gray-500 text-base leading-relaxed group-hover:text-white/90 transition-colors">
                            Quả là cơ quan sinh sản của cây, hình thành từ bầu nhụy sau khi hoa được thụ phấn. Ví dụ như cà chua, ớt, bí...
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Benefits & Tips Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Left Column: Nutrition & Benefits -->
            <div class="space-y-8">
                <div class="bg-soft-green p-10 rounded-[2.5rem] relative overflow-hidden">
                    <div class="relative z-10 space-y-6">
                        <h3 class="text-2xl font-black text-green-900 flex items-center gap-3">
                            <i class="fa fa-heartbeat text-red-500"></i>
                            Lợi ích sức khỏe
                        </h3>
                        <ul class="grid grid-cols-1 gap-4">
                            <li class="flex items-center gap-3 text-gray-700 font-medium">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Giàu Vitamin A, B, C, E, K và khoáng chất
                            </li>
                            <li class="flex items-center gap-3 text-gray-700 font-medium">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Hỗ trợ giảm cân và cải thiện hệ tiêu hóa
                            </li>
                            <li class="flex items-center gap-3 text-gray-700 font-medium">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Tăng cường sức đề kháng và thị lực
                            </li>
                            <li class="flex items-center gap-3 text-gray-700 font-medium">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Điều hòa huyết áp và đường huyết
                            </li>
                        </ul>
                    </div>
                    <i class="fa fa-leaf absolute -bottom-10 -right-10 text-green-200/30 text-[15rem] rotate-12"></i>
                </div>
            </div>

            <!-- Right Column: How to Choose -->
            <div class="space-y-8">
                <h3 class="text-2xl font-black text-gray-900 flex items-center gap-3 px-4">
                    Cách chọn củ quả tươi ngon
                </h3>
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-start gap-5 p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 shrink-0 bg-orange-100 rounded-xl flex items-center justify-center text-orange-600">
                            <i class="fa fa-eye"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Dựa vào hình dáng</h4>
                            <p class="text-gray-500 text-xs leading-relaxed">Chọn củ quả không sâu bệnh, cuống tươi, vỏ không bị nhũn.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-5 p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 shrink-0 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600">
                            <i class="fa fa-tint"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Màu sắc tự nhiên</h4>
                            <p class="text-gray-500 text-xs leading-relaxed">Màu sắc tươi mới, không quá bóng bẩy hay héo úa.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-5 p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 shrink-0 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                            <i class="fa fa-hand-paper"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 mb-1">Cảm nhận khi cầm</h4>
                            <p class="text-gray-500 text-xs leading-relaxed">Cầm chắc tay, nặng và có kích thước vừa phải.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Products Highlight -->
        <div class="bg-gray-900 rounded-[3rem] p-12 text-white relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-10">
                <div class="space-y-4 max-w-lg">
                    <span class="bg-green-500 text-xs font-black px-4 py-1.5 rounded-full uppercase tracking-widest">Sản phẩm ưa chuộng</span>
                    <h3 class="text-4xl font-black leading-tight">Chất Lượng Đến Từ Nông Trại Sạch</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Từ Cà rốt Đà Lạt, Khoai tây bùi ngọt đến Cà chua chín mọng - mỗi sản phẩm tại Gimi đều được tuyển chọn khắt khe để đảm bảo an toàn tuyệt đối.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4 shrink-0">
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/10 text-center">
                        <span class="text-3xl block mb-2 font-black text-green-400">100%</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-300">Organic</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-md p-6 rounded-3xl border border-white/10 text-center">
                        <span class="text-3xl block mb-2 font-black text-green-400">Fresh</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-300">Daily</span>
                    </div>
                </div>
            </div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-green-500/20 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/2"></div>
        </div>
    </section>
@endsection
