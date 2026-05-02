<!doctype html>
<html class="no-js" lang="zxx">

<!-- index-231:32-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    @hasSection('title')
        <title>@yield('title') - {{ env('APP_NAME') }}</title>
    @else
        <title>{{ env('APP_NAME') }}</title>
    @endif

    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('theme/user/images/favicon.png') }}">
    <!-- Material Design Iconic Font-V2.2.0 -->
    <link rel="stylesheet" href="{{ asset('theme/user/css/material-design-iconic-font.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('theme/user/css/font-awesome.min.css') }}">
    <!-- Font Awesome Stars-->
    <link rel="stylesheet" href="{{ asset('theme/user/css/fontawesome-stars.css') }}">
    <!-- Meanmenu CSS -->
    <link rel="stylesheet" href="{{ asset('theme/user/css/meanmenu.css') }}">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="{{ asset('theme/user/css/owl.carousel.min.css') }}">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" href="{{ asset('theme/user/css/slick.css') }}">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="{{ asset('theme/user/css/animate.css') }}">
    <!-- Jquery-ui CSS -->
    <link rel="stylesheet" href="{{ asset('theme/user/css/jquery-ui.min.css') }}">
    <!-- Venobox CSS -->
    <link rel="stylesheet" href="{{ asset('theme/user/css/venobox.css') }}">
    <!-- Nice Select CSS -->
    <link rel="stylesheet" href="{{ asset('theme/user/css/nice-select.css') }}">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="{{ asset('theme/user/css/magnific-popup.css') }}">
    <!-- Bootstrap V4.1.3 Fremwork CSS -->
    <link rel="stylesheet" href="{{ asset('theme/user/css/bootstrap.min.css') }}">
    <!-- Helper CSS -->
    <link rel="stylesheet" href="{{ asset('theme/user/css/helper.css') }}">
    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('theme/user/style.css') }}">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="{{ asset('theme/user/css/responsive.css') }}">
    <!-- Modernizr js -->
    <script src="{{ asset('theme/user/js/vendor/modernizr-2.8.3.min.js') }}"></script>

    {{-- <link rel="stylesheet" href="{{ asset('lib/fontawesome/css/all.css') }}"> --}}

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500&display=swap" rel="stylesheet">

    <style>
        .carousel-inner img {
            width: 100%;
            height: 100%;
        }

        #vegPrefForm {
            display: flex;
            flex-direction: column;
            height: 600px;
        }

        #vegPrefForm .modal-body {
            flex: 1 1 auto;
            overflow-y: auto;
            padding-bottom: 15px;
        }

        #vegPrefForm .modal-footer {
            flex-shrink: 0;
        }

        #dish_recipe_content {
            white-space: pre-wrap;
            border: 1px solid #eee;
            padding: 12px;
            display: none;
            background: #fff;
            max-height: 340px;
            overflow-y: auto;
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
        }

        /* loader container */
        .ai-loader {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #333;
            justify-content: center;
            height: 100%;
        }

        /* three dots */
        .ai-loader .dots {
            display: inline-block;
            width: 28px;
            text-align: left;
        }

        .ai-loader .dots span {
            display: inline-block;
            width: 6px;
            height: 6px;
            margin-right: 4px;
            background: #007bff;
            border-radius: 50%;
            opacity: 0.2;
            transform: translateY(0);
            animation: dot 1s infinite linear;
        }

        .ai-loader .dots span:nth-child(1) {
            animation-delay: 0s;
        }

        .ai-loader .dots span:nth-child(2) {
            animation-delay: 0.12s;
        }

        .ai-loader .dots span:nth-child(3) {
            animation-delay: 0.24s;
        }

        @keyframes dot {
            0% {
                opacity: 0.2;
                transform: translateY(0);
            }

            30% {
                opacity: 1;
                transform: translateY(-6px);
            }

            60% {
                opacity: 0.6;
                transform: translateY(0);
            }

            100% {
                opacity: 0.2;
                transform: translateY(0);
            }
        }

        /* ensure suggestions list sits under input inside modal */
        #dish_suggestions {
            max-height: 250px;
            overflow-y: auto;
        }
    </style>


</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
    <!-- Begin Body Wrapper -->
    <div class="body-wrapper">
        <!-- Begin Header Area -->
        <header>
            <!-- Begin Header Top Area -->
            <div class="header-top">
                <div class="container">
                    <div class="row">
                        <!-- Begin Header Top Left Area -->
                        <div class="col-lg-3 col-md-4">
                            <div class="header-top-left">
                                <ul class="phone-wrap">
                                    <li><span>Điện thoại:</span><a href="#">(+84) 584246834</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- Header Top Left Area End Here -->
                        <!-- Begin Header Top Right Area -->
                        <div class="col-lg-9 col-md-8">
                            <div class="header-top-right">
                                <ul class="ht-menu">
                                    <!-- Begin Setting Area -->
                                    <li>
                                        @if (\Illuminate\Support\Facades\Auth::guard('web')->check())
                                            <div class="ht-setting-trigger">
                                                <span>{{ \Illuminate\Support\Facades\Auth::guard('web')->user()->name }}</span>
                                            </div>
                                            <div class="setting ht-setting">
                                                <ul class="ht-setting-list">
                                                    <li><a href="{{ route('web.profile') }}">Tài khoản</a></li>
                                                    <li><a href="{{ route('web.list_order_of_user') }}">Danh sách đơn
                                                            đặt hàng</a></li>
                                                    <li><a href="{{ route('web.logout') }}">Đăng xuất</a></li>
                                                </ul>
                                            </div>
                                        @else
                                            <div class="ht-setting-trigger"><span>Đăng nhập để mua hàng</span></div>
                                            <div class="setting ht-setting">
                                                <ul class="ht-setting-list">
                                                    <li><a href="{{ route('web.register') }}">Đăng kí</a></li>
                                                    <li><a href="{{ route('web.login') }}">Đăng nhập</a></li>
                                                </ul>
                                            </div>
                                        @endif

                                    </li>
                                    <!-- Setting Area End Here -->
                                </ul>
                            </div>
                        </div>
                        <!-- Header Top Right Area End Here -->
                    </div>
                </div>
            </div>
            <!-- Header Top Area End Here -->
            <!-- Begin Header Middle Area -->
            <div class="header-middle pl-sm-0 pr-sm-0 pl-xs-0 pr-xs-0">
                <div class="container">
                    <div class="row">
                        <!-- Begin Header Logo Area -->
                        <div class="col-lg-3">
                            <div class="logo pb-sm-30 pb-xs-30">
                                <a href="{{ route('web.index') }}">
                                    <img src="{{ asset('theme/user/images/menu/logo/nongsan.png') }}" alt="Footer Logo"
                                        style="width: 100px; height: auto;">
                                </a>
                            </div>
                        </div>
                        <!-- Header Logo Area End Here -->
                        <!-- Begin Header Middle Right Area -->
                        <div class="col-lg-9 pl-0 ml-sm-15 ml-xs-15">
                            <!-- Begin Header Middle Searchbox Area -->
                            <form action="{{ route('web.search') }}" class="hm-searchbox">
                                <input type="text" placeholder="Hôm nay mua sắm thực phẩm gì?" name="search"
                                    value="{{ request()->get('search') ?? '' }}">
                                <button class="li-btn"><i class="fa fa-search"></i></button>
                            </form>
                            <!-- Header Middle Searchbox Area End Here -->
                            <!-- Begin Header Middle Right Area -->
                            <div class="header-middle-right">
                                <ul class="hm-menu" id="cart-icon">
                                    <!-- Begin Header Mini Cart Area -->
                                    <li class="hm-minicart">
                                        <div class="hm-minicart-trigger">
                                            <span class="item-icon"></span>
                                            <span class="item-text">Giỏ hàng<span class="cart-item-count">
                                                    @if (\Illuminate\Support\Facades\Auth::guard('web')->check())
                                                        {{ \Illuminate\Support\Facades\Auth::guard('web')->user()->countListProductInCart() }}
                                                    @else
                                                        0
                                                    @endif
                                                </span>
                                            </span>
                                        </div>
                                    </li>
                                    <!-- Header Mini Cart Area End Here -->
                                </ul>
                            </div>
                            <!-- Header Middle Right Area End Here -->
                        </div>
                        <!-- Header Middle Right Area End Here -->
                    </div>
                </div>
            </div>
            <!-- Header Middle Area End Here -->
            <!-- Begin Header Bottom Area -->
            <div class="header-bottom header-sticky d-none d-lg-block">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Begin Header Bottom Menu Area -->
                            <div class="hb-menu hb-menu-2 d-xl-block">
                                <nav>
                                    <ul>
                                        <li><a href="{{ route('web.index') }}">Trang chủ</a></li>
                                        <li><a href="{{ route('web.about') }}">Về chúng tôi</a></li>
                                        <li><a href="{{ route('web.contact') }}">Liên hệ</a></li>
                                        <li>
                                            <a href="#" data-toggle="modal" data-target="#vegPrefModal"
                                                class="hb-menu-link">
                                                🤖 AI Gợi ý mua sắm
                                            </a>

                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <!-- Header Bottom Menu Area End Here -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header Bottom Area End Here -->
            <!-- Begin Mobile Menu Area -->
            <div class="mobile-menu-area d-lg-none d-xl-none col-12">
                <div class="container">
                    <div class="row">
                        <div class="mobile-menu">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu Area End Here -->
        </header>
        <!-- Header Area End Here -->
        <div class="modal fade" id="vegPrefModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content border-0">

                    {{-- <form id="vegPrefForm" class="needs-validation" novalidate>
                        @csrf

                        <div class="modal-header py-3" style="border-bottom: 1px solid #eee;">
                            <h5 class="modal-title" style="font-weight:600;">🤖 AI Gợi ý mua sắm</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        @php
                            use App\Models\Category;
                            $categories = Category::orderBy('name')->get();
                        @endphp

                        <div class="modal-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label class="small mb-1">Danh mục ưa thích</label>
                                    <select name="category_id" class="form-control" multiple>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="small mb-1">Cách dùng</label>
                                    <select name="uses[]" class="form-control" multiple>
                                        <option value="salad">Salad</option>
                                        <option value="stir-fry">Xào</option>
                                        <option value="soup">Nấu canh</option>
                                        <option value="grill">Nướng</option>
                                        <option value="juice">Ép/Detox</option>
                                        <option value="hotpot">Lẩu</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="small mb-1">Organic</label>
                                    <select name="organic" class="form-control">
                                        <option value="either">Không quan trọng</option>
                                        <option value="yes">Có</option>
                                        <option value="no">Không</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="small mb-1">Nguồn gốc</label>
                                    <select name="origin" class="form-control">
                                        <option value="">Không quan trọng</option>
                                        <option value="local">Nội địa</option>
                                        <option value="import">Nhập khẩu</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="small mb-1">Khoảng giá (VND)</label>
                                    <div class="form-row">
                                        <div class="col">
                                            <input type="number" name="price_min" class="form-control"
                                                placeholder="Từ">
                                        </div>
                                        <div class="col">
                                            <input type="number" name="price_max" class="form-control"
                                                placeholder="Đến">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-12">
                                    <label class="small mb-1 d-flex align-items-center">Mức “tươi” <span
                                            class="ml-2 badge badge-light" id="freshnessLabel">8</span></label>
                                    <input type="range" name="freshness" class="custom-range" min="1"
                                        max="10" value="8"
                                        oninput="document.getElementById('freshnessLabel').innerText=this.value">
                                    <small class="form-text text-muted">1 = không quá tươi, 10 = rất tươi</small>
                                </div>
                            </div>

                            <hr class="my-3">

                            <div id="vegResults" class="row"></div>
                        </div>

                        <div class="modal-footer" style="border-top: 1px solid #eee;">
                            <button type="button" id="btnRecommend" class="btn btn-primary btn-sm">
                                Gợi ý ngay
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
                                Đóng
                            </button>
                        </div>
                    </form>  --}}


                    <form id="vegPrefForm" class="needs-validation" novalidate style="height: 600px" method="POST"
                        action="{{ route('web.ai.recommend') }}">

                        @csrf
                        <div class="modal-header py-3" style="border-bottom: 1px solid #eee;">
                            <h5 class="modal-title" style="font-weight:600;">🤖 AI Gợi ý mua sắm</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold">Bạn muốn nấu món gì?</label>
                                <input type="text" id="dish_name" name="dish_name" class="form-control"
                                    placeholder="Ví dụ: Cá chiên, canh rau củ, salad trộn..." autocomplete="off">
                                <div id="dish_suggestions" class="list-group position-absolute"
                                    style="z-index:1000; width:93%"></div>
                            </div>

                            <!-- Hidden input chứa công thức để submit -->
                            <input type="hidden" name="dish_recipe" id="dish_recipe_hidden">

                            <div id="dish_recipe_loader" class="ai-loader">
                                <div>🤖 AI đang đợi bạn nhập món</div>
                                <div class="dots"><span></span><span></span></div>
                            </div>

                            <div id="dish_recipe_content" style="display:none; white-space:pre-wrap;"></div>
                            <div id="dish_recipe_notfound" style="display:none; color:#666;">
                                Không tìm thấy công thức cho món này.
                            </div>
                        </div>

                        <div class="modal-footer" style="border-top: 1px solid #eee;">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Gợi ý nguyên liệu
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">
                                Đóng
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- Styles nhỏ cho z-index & card --}}
        <style>
            .header-sticky {
                z-index: 100;
            }

            .modal-backdrop {
                z-index: 1050;
            }

            .modal {
                z-index: 1060;
            }

            .veg-card {
                border: 1px solid #eee;
                border-radius: .5rem;
                height: 100%;
                padding: 1rem;
            }

            .veg-title {
                font-weight: 600;
                font-size: .95rem;
                margin-bottom: .25rem;
            }

            .veg-meta {
                font-size: .8rem;
                color: #6c757d;
                margin-bottom: .5rem;
            }
        </style>

        <div class="mb-5">
            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif
            @yield('content')
        </div>
        <div class="sale-banner-container"
            style="
    /* CSS cho Banner */
    background-color: #FFEE00; 
    display: flex;
    align-items: center;
    padding: 15px 20px;
    font-family: Arial, sans-serif;
    max-width: 1200px; 
    margin: 20px auto;
">
            <div class="banner-image-wrap"
                style="
        padding: 5px;
        background-color: white; 
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    ">
                <img src="" alt="" class="banner-image"
                    style="
            width: 120px; 
            height: auto;
            border-radius: 4px;
            display: block;
        ">
            </div>

            <div class="banner-content"
                style="
        flex-grow: 1;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-left: 20px;
    ">
                <h2 class="product-title"
                    style="
            font-size: 50px;
            font-weight: 900;
            color: #007D3E; 
            text-shadow: 3px 3px 0 #FFFFFF; 
            line-height: 1;
            margin: 0;
            padding-right: 20px;
        ">
                    THỊT XAY
                </h2>

                <div class="price-and-condition" style="
            text-align: right;
        ">
                    <span class="sale-price"
                        style="
                font-size: 75px;
                font-weight: 900;
                color: #D30000; 
                text-shadow: 4px 4px 0 rgba(0, 0, 0, 0.2); 
                line-height: 1;
                display: inline-block;
            ">
                        9.<span style="font-size: 0.6em; margin-right: 5px;">000</span>
                    </span>
                    <span class="vnd-symbol"
                        style="
                font-size: 40px;
                font-weight: 900;
                vertical-align: top;
                margin-right: -10px;
                color: #D30000;
            ">
                        đ
                    </span>
                    <span class="unit"
                        style="
                font-size: 30px;
                font-weight: 700;
                color: #D30000;
                vertical-align: top;
                margin-left: 5px;
            ">
                        /100g
                    </span>

                    <p class="condition"
                        style="
                font-size: 28px;
                font-weight: bold;
                color: #D30000;
                text-shadow: 1px 1px 0 rgba(0, 0, 0, 0.1);
                margin-top: 5px;
                margin-bottom: 0;
            ">
                        Khi mua kèm rau, củ, nấm
                    </p>
                </div>
            </div>
        </div>
        <div style="text-align: center; margin: 0 auto; max-width: 1800px; padding: 20px;">

            <h2 style="color: #4CAF50;">Củ, quả là gì?</h2>
            <p>
                <strong>Củ, quả</strong> là hai bộ phận khác nhau của thực vật, thường được chúng ta sử dụng làm thực
                phẩm.
            </p>

            <h3 style="text-align: left; color: #4CAF50;">• Củ là gì?</h3>
            <p style="text-align: left; margin-left: 20px;">
                Củ là phần phình to của rễ hoặc thân cây (tùy loại), có chức năng dự trữ chất dinh dưỡng để nuôi cây
                phát triển (khoai lang, khoai tây, cà rốt, củ cải, củ gừng, củ hành, củ tỏi,...). Có củ mọc dưới đất
                (khoai lang, cà rốt), có củ mọc gần mặt đất hoặc ngay phần gốc thân.
            </p>

            <h3 style="text-align: left; color: #4CAF50;">• Quả là gì?</h3>
            <p style="text-align: left; margin-left: 20px;">
                Quả là cơ quan sinh sản của cây, hình thành từ bầu nhụy sau khi hoa được thụ phấn. Bên trong quả thường
                chứa hạt, giúp cây duy trì và phát tán giống. Ví dụ: Cà chua, ớt, bí,...
            </p>

            <hr style="border: 1px solid #ccc; width: 100%; margin: 20px auto;">

            <h2 style="text-align: left; color: #4CAF50;">Thành phần dinh dưỡng của củ, quả</h2>
            <p>
                Các loại củ, quả đa dạng màu sắc sẽ cung cấp phần lớn là **nước** cho cơ thể bạn, bởi trong các loại rau
                củ chứa khoảng **70 - 95% là nước**. Bên cạnh đó củ, quả còn là nguồn cung cấp các chất **bột đường**,
                các **vitamin A, B, C, E, K…** và các **khoáng chất** cần thiết cho sự phát triển của cơ thể.
            </p>

            <hr style="border: 1px solid #ccc; width: 80%; margin: 20px auto;">

            <h2 style="text-align: left; color: #4CAF50;">Lợi ích của củ, quả cho sức khỏe</h2>
            <ul style="text-align: left; margin-left: 10%; list-style-type: disc;">
                <li>Củ, quả chứa rất nhiều **vitamin và chất dinh dưỡng** nên mang đến rất nhiều lợi ích cho sức khỏe
                    con người.</li>
                <li>**Hỗ trợ giảm cân**</li>
                <li>Giảm nguy cơ mắc các bệnh về **tim mạch, béo phì và cả ung thư**</li>
                <li>Tăng cường **sức đề kháng** của cơ thể</li>
                <li>**Cải thiện thị lực**</li>
                <li>**Điều hòa đường huyết**</li>
                <li>Giảm **cholesterol** trong máu</li>
                <li>**Điều hòa huyết áp**</li>
            </ul>

            <hr style="border: 1px solid #ccc; width: 80%; margin: 20px auto;">

            <h2 style="text-align: left; color: #4CAF50;">Cách chọn củ, quả tươi ngon</h2>
            <ul style="text-align: left; margin-left: 10%; list-style-type: disc;">
                <li>**Dựa vào hình dáng bên ngoài:** Nên ưu tiên chọn các loại củ, quả có phần vỏ không có các vết sâu,
                    cuống lá không bị nhũn, thâm đen. Tránh chọn những loại quả có vẻ ngoài to tròn, căng lớn, bởi đây
                    có thể là những quả đã bị tiêm thuốc, không an toàn cho sức khỏe.</li>
                <li>**Dựa vào màu sắc:** Màu sắc của các loại củ, quả thường tươi mới, không có các vết xước, héo hay
                    quá đậm màu. Các loại củ có màu quá xanh hoặc quá bóng sẽ không hẳn là an toàn cho sức khỏe người
                    dùng.</li>
                <li>**Dựa vào mùi hương:** Mùi hương tự nhiên của các loại củ, quả sẽ là mùi đặc trưng theo từng loại
                    chứ không phải là mùi hắc, nồng khó chịu. Nếu các loại củ bạn đang chọn có mùi hóa chất hãy ngưng sử
                    dụng ngay.</li>
                <li>**Dựa vào cảm nhận khi cầm:** Những loại củ quả cầm chắc tay, kích thước vừa phải, không quá to sẽ
                    thường ngon hơn những loại to lớn nhưng khối lượng nhẹ. Một số loại rau củ bạn chỉ nên chọn những
                    quả nhỏ, đều tay sẽ ngon hơn.</li>
            </ul>

            <hr style="border: 1px solid #ccc; width: 80%; margin: 20px auto;">

            <h2 style="text-align: left; color: #4CAF50;">Các loại củ, quả</h2>
            <ul style="text-align: left; margin-left: 10%; list-style-type: disc;">
                <li>**Củ, quả hầm, luộc:** Cà rốt, khoai tây, củ dền, bí đỏ, củ sắn,...</li>
                <li>**Củ, quả xào:** Cà chua, bạc hà, đậu bắp,...</li>
                <li>**Củ, quả nấu canh:** Su hào, mướp, bắp mỹ, bí đao,... </li>
                <li>**Củ, quả trộn salad, gỏi:** Bắp cải, cà chua, dưa leo,...</li>
            </ul>

            <hr style="border: 1px solid #ccc; width: 80%; margin: 20px auto;">

            <h2 style="text-align: left; color: #4CAF50;">Các sản phẩm củ, quả được ưa chuộng nhất hiện nay</h2>
            <ul style="text-align: left; margin-left: 10%; list-style-type: disc;">
                <li>**Cà rốt:** Đây là loại củ quen thuộc trong bữa ăn hằng ngày. Cà rốt Đà Lạt có vị tươi ngọt, ít sử
                    dụng phân thuốc nên rất an toàn cho sức khỏe người tiêu dùng.</li>
                <li>**Khoai tây:** Khoai tây là nguồn bổ sung tinh bột dồi dào cho cơ thể. Hương vị ngọt bùi nên thích
                    hợp với các món hầm bổ dưỡng. Củ khoai to tròn, vỏ mỏng và không sâu úng. </li>
                <li>**Cà chua:** Cà chua có nguồn gốc Lâm Đồng trái cà tròn đều và dày cơm. Cà chua chứa nhiều chất
                    chống oxy hóa, làm đẹp da cho chị em phụ nữ.</li>
                <li>**Su su:** Quả su giòn và ngọt thích hợp với các món xào hoặc luộc đều rất ngon. Su có nguồn gốc từ
                    Lâm Đồng, đảm bảo tươi và an toàn cho sức khỏe.</li>
                <li>**Bí xanh:** Bí xanh tươi cung cấp nhiều chất xơ, nước cho cơ thể. Bí thích hợp với các món canh,
                    hầm bổ dưỡng.</li>
            </ul>
        </div>
        <!-- Begin Footer Area -->
        <div class="footer">
            <!-- Begin Footer Static Top Area -->
            <div class="footer-static-top">
                <div class="container">
                    <!-- Khu vực dịch vụ giao hàng -->
                    <div class="footer-shipping pt-60 pb-55 pb-xs-25">
                        <div class="row">
                            <!-- Bắt đầu hộp dịch vụ giao hàng -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-sm-55 pb-xs-55">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="{{ asset('theme/user/images/shipping-icon/1.png') }}"
                                            alt="Biểu tượng giao hàng">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Giao hàng miễn phí</h2>
                                        <p>Và miễn phí đổi trả. Xem chi tiết thời gian giao hàng khi thanh
                                            toán.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Kết thúc hộp dịch vụ giao hàng -->
                            <!-- Bắt đầu hộp dịch vụ thanh toán an toàn -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-sm-55 pb-xs-55">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="{{ asset('theme/user/images/shipping-icon/2.png') }}"
                                            alt="Biểu tượng thanh toán">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Thanh toán an toàn</h2>
                                        <p>Thanh toán với các phương thức phổ biến và bảo mật nhất thế giới.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Kết thúc hộp dịch vụ thanh toán an toàn -->
                            <!-- Bắt đầu hộp dịch vụ mua sắm an tâm -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-xs-30">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="{{ asset('theme/user/images/shipping-icon/3.png') }}"
                                            alt="Biểu tượng bảo vệ người mua">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Mua sắm an tâm</h2>
                                        <p>Chính sách bảo vệ người mua hỗ trợ bạn từ lúc đặt hàng đến khi
                                            nhận hàng.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Kết thúc hộp dịch vụ mua sắm an tâm -->
                            <!-- Bắt đầu hộp dịch vụ hỗ trợ 24/7 -->
                            <div class="col-lg-3 col-md-6 col-sm-6 pb-xs-30">
                                <div class="li-shipping-inner-box">
                                    <div class="shipping-icon">
                                        <img src="{{ asset('theme/user/images/shipping-icon/4.png') }}"
                                            alt="Biểu tượng hỗ trợ khách hàng">
                                    </div>
                                    <div class="shipping-text">
                                        <h2>Trung tâm hỗ trợ 24/7</h2>
                                        <p>Có thắc mắc? Gọi ngay cho chúng tôi hoặc trò chuyện trực tuyến.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Kết thúc hộp dịch vụ hỗ trợ 24/7 -->
                        </div>
                    </div>
                    <!-- Kết thúc khu vực dịch vụ giao hàng -->
                </div>
            </div>

            <!-- Footer Static Top Area End Here -->
            <!-- Begin Footer Static Middle Area -->
            <div class="footer-static-middle">
                <div class="container">
                    <div class="footer-logo-wrap pt-50 pb-35">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="footer-logo">
                                    <img src="{{ asset('theme/user/images/menu/logo/nongsan.png') }}"
                                        alt="Logo Nông Sản Xanh" style="width: 100px; height: auto;">
                                    <h4 style="color: #4CAF50; margin-top: 10px;">NÔNG SẢN XANH</h4>
                                </div>

                                <ul class="des" style="list-style: none; padding-left: 0;">
                                    <li>
                                        <span>Địa chỉ Trang trại & Cửa hàng: </span>
                                        **Khu Nông Nghiệp Công Nghệ Cao, Lô A1, TP.HCM** (Thay thế địa chỉ cụ thể)
                                    </li>
                                    <li>
                                        <span>Hotline Đặt hàng: </span>
                                        <a href="tel:+84584246834">(+84) 584 246 834</a>
                                    </li>
                                    <li>
                                        <span>Email Hỗ trợ: </span>
                                        <a href="mailto:hoahuongduong05124@gmail.com">hotro@nongsanxanh.vn</a> (Thay
                                        đổi email liên quan)
                                    </li>
                                    <li>
                                        <span>Giờ làm việc: </span>
                                        **8:00 - 18:00 (Thứ 2 - Thứ 7)**
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-2 col-md-3 col-sm-6">
                                <div class="footer-block">
                                    <h4 class="footer-block-title" style="color: #4CAF50;">Chính sách & Cam kết</h4>
                                    <ul>
                                        <li><a href="#">Chính sách Đổi/Trả hàng</a></li>
                                        <li><a href="#">Cam kết Nông sản Sạch</a></li>
                                        <li><a href="#">Hướng dẫn Đặt hàng Online</a></li>
                                        <li><a href="#">Phương thức Thanh toán</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6">
                                <div class="footer-block">
                                    <h4 class="footer-block-title" style="color: #4CAF50;">Dịch vụ & Cộng đồng</h4>
                                    <ul>
                                        <li><a href="#">Tin tức Nông nghiệp</a></li>
                                        <li><a href="#">Tham quan Trang trại</a></li>
                                        <li><a href="#">Tuyển dụng</a></li>
                                        <li><a href="#">Quy chế Sàn giao dịch</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="footer-block">
                                    <h4 class="footer-block-title" style="color: #4CAF50;">Kết nối với Nông Sản Xanh
                                    </h4>
                                    <ul class="social-link"
                                        style="list-style: none; padding-left: 0; display: flex; gap: 10px;">
                                        <li class="facebook">
                                            <a href="https://www.facebook.com/NongSanXanhVN" data-toggle="tooltip"
                                                target="_blank" title="Facebook">
                                                <i class="fa fa-facebook"
                                                    style="font-size: 24px; color: #3b5998;"></i>
                                            </a>
                                        </li>
                                        <li class="youtube">
                                            <a href="https://www.youtube.com/" data-toggle="tooltip" target="_blank"
                                                title="Youtube">
                                                <i class="fa fa-youtube" style="font-size: 24px; color: #ff0000;"></i>
                                            </a>
                                        </li>
                                        <li class="instagram">
                                            <a href="https://www.instagram.com/nongsanxanh" data-toggle="tooltip"
                                                target="_blank" title="Instagram">
                                                <i class="fa fa-instagram"
                                                    style="font-size: 24px; color: #C13584;"></i>
                                            </a>
                                        </li>
                                        <li class="zalo">
                                            <a href="https://zalo.me/" data-toggle="tooltip" target="_blank"
                                                title="Zalo">
                                                <i class="fa fa-comment" style="font-size: 24px; color: #0084ff;"></i>
                                            </a>
                                        </li>
                                    </ul>
                                    <p style="margin-top: 20px;">Đăng ký nhận **Ưu đãi Nông sản mới**</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Static Middle Area End Here -->
            <!-- Begin Footer Static Bottom Area -->
            <div class="footer-static-bottom pt-55 pb-55">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Begin Copyright Area -->
                            <div class="copyright text-center pt-25">
                                &copy; Copyright <strong><span>Website nông sản AI</span></strong>. All Rights Reserved
                            </div>
                            <!-- Copyright Area End Here -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Static Bottom Area End Here -->
        </div>
        <script src="https://app.tudongchat.com/js/chatbox.js"></script>
        <script>
            const tudong_chatbox = new TuDongChat('5mTKohbMB-i-PcTxM_iHg')
            tudong_chatbox.initial()
        </script>
        <!-- Footer Area End Here -->
    </div>
    <!-- Body Wrapper End Here -->
    <!-- jQuery-V1.12.4 -->
    <script src="{{ asset('theme/user/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <!-- Popper js -->
    <script src="{{ asset('theme/user/js/vendor/popper.min.js') }}"></script>
    <!-- Bootstrap V4.1.3 Fremwork js -->
    <script src="{{ asset('theme/user/js/bootstrap.min.js') }}"></script>
    <!-- Ajax Mail js -->
    <script src="{{ asset('theme/user/js/ajax-mail.js') }}"></script>
    <!-- Meanmenu js -->
    <script src="{{ asset('theme/user/js/jquery.meanmenu.min.js') }}"></script>
    <!-- Wow.min js -->
    <script src="{{ asset('theme/user/js/wow.min.js') }}"></script>
    <!-- Slick Carousel js -->
    <script src="{{ asset('theme/user/js/slick.min.js') }}"></script>
    <!-- Owl Carousel-2 js -->
    <script src="{{ asset('theme/user/js/owl.carousel.min.js') }}"></script>
    <!-- Magnific popup js -->
    <script src="{{ asset('theme/user/js/jquery.magnific-popup.min.js') }}"></script>
    <!-- Isotope js -->
    <script src="{{ asset('theme/user/js/isotope.pkgd.min.js') }}"></script>
    <!-- Imagesloaded js -->
    <script src="{{ asset('theme/user/js/imagesloaded.pkgd.min.js') }}"></script>
    <!-- Mixitup js -->
    <script src="{{ asset('theme/user/js/jquery.mixitup.min.js') }}"></script>
    <!-- Countdown -->
    <script src="{{ asset('theme/user/js/jquery.countdown.min.js') }}"></script>
    <!-- Counterup -->
    <script src="{{ asset('theme/user/js/jquery.counterup.min.js') }}"></script>
    <!-- Waypoints -->
    <script src="{{ asset('theme/user/js/waypoints.min.js') }}"></script>
    <!-- Barrating -->
    <script src="{{ asset('theme/user/js/jquery.barrating.min.js') }}"></script>
    <!-- Jquery-ui -->
    <script src="{{ asset('theme/user/js/jquery-ui.min.js') }}"></script>
    <!-- Venobox -->
    <script src="{{ asset('theme/user/js/venobox.min.js') }}"></script>
    <!-- Nice Select js -->
    <script src="{{ asset('theme/user/js/jquery.nice-select.min.js') }}"></script>
    <!-- ScrollUp js -->
    <script src="{{ asset('theme/user/js/scrollUp.min.js') }}"></script>
    <!-- Main/Activator js -->
    <script src="{{ asset('theme/user/js/main.js') }}"></script>
    <script src="{{ asset('js/loadingoverlay.min.js') }}"></script>
    <script src="{{ asset('lib/select2/js/select2.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('lib/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/sweetalert2/sweetalert2.min.css') }}">
    <script src="{{ asset('lib/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#cart-icon').click(function() {
                window.location.replace(@json(route('web.list.product.cart')));
            });
        });

        function formatVnd(num) {
            return num.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") + 'đ';
        }
    </script>

    <script>
        (function($) {
            var $form = $('#vegPrefForm');
            var $results = $('#vegResults');

            // mỗi lần mở modal, xoá kết quả cũ (tùy chọn)
            $('#vegPrefModal').on('shown.bs.modal', function() {
                $results.empty();
            });

            $('#btnRecommend').on('click', function() {
                // đảm bảo không có submit mặc định
                // (vì nút là type="button" nên không submit)
                var catVal = $form.find('select[name="category_id"]').val();
                var category_id = Array.isArray(catVal) ? (catVal[0] || '') : (catVal || '');

                if (!category_id) {
                    $results.html('<div class="col-12 text-danger">Vui lòng chọn danh mục.</div>');
                    return;
                }

                $results.html('<div class="col-12 text-muted">Đang tải sản phẩm...</div>');

                $.ajax({
                    url: "{{ route('web.recommend') }}", // ĐẢM BẢO route này tồn tại (mục 3)
                    type: "POST",
                    data: JSON.stringify({
                        category_id: category_id
                    }),
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $form.find('input[name="_token"]').val()
                    },
                    success: function(res) {
                        if (!res.items || !res.items.length) {
                            $results.html(
                                '<div class="col-12 text-muted">Chưa có sản phẩm trong danh mục này.</div>'
                            );
                            return;
                        }
                        var html = res.items.map(function(item) {
                            return `
              <div class="col-sm-6 col-md-6 col-lg-6 mb-6">
                <div class="veg-card">
                  ${item.thumbnail ? `<div class="mb-2 text-center"><img src="${item.thumbnail}" alt="${item.name}" style="max-width:100%;height:auto"></div>` : ``}
                  <div class="veg-title">${item.name}</div>
                  <div class="veg-meta">${item.category_name || ''}</div>
                  <div>Giá: <strong>${new Intl.NumberFormat('vi-VN').format(item.price)} ₫</strong></div>
                  ${typeof item.stock !== 'undefined' ? `<div class="mb-2">Còn: ${item.stock}</div>` : ``}
                  ${item.url ? `<a href="${item.url}" class="btn btn-success btn-sm btn-block">Xem chi tiết</a>` : ``}
                </div>
              </div>
            `;
                        }).join('');
                        $results.html(html);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText || xhr.statusText);
                        $results.html(
                            '<div class="col-12 text-danger">Có lỗi xảy ra. Vui lòng thử lại.</div>'
                        );
                    }
                });
            });
        })(jQuery);
    </script>

    <script>
        $(function() {
            // helper: show loader area
            function showLoader() {
                $('#dish_recipe').show();
                $('#dish_recipe_content').hide();
                $('#dish_recipe_notfound').hide();
                $('#dish_recipe_loader').show();
            }

            function showRecipe(text) {
                $('#dish_recipe_loader').hide();

                $('#dish_recipe_hidden').val(text);

                if (text && text.trim().length) {
                    $('#dish_recipe_content').text(text).show();
                    $('#dish_recipe_notfound').hide();
                } else {
                    $('#dish_recipe_content').hide();
                    $('#dish_recipe_notfound').show();
                }
            }


            function hideRecipe() {
                $('#dish_recipe').hide();
                // $('#dish_recipe_loader').hide();
                $('#dish_recipe_content').hide();
                $('#dish_recipe_notfound').hide();
            }

            // Autocomplete: fetch suggestions
            var ajaxSuggest;
            $('#dish_name').on('keyup', function() {
                var q = $(this).val().trim();
                if (q.length < 1) {
                    $('#dish_suggestions').hide().empty();
                    hideRecipe();
                    return;
                }

                if (ajaxSuggest) ajaxSuggest.abort();
                ajaxSuggest = $.ajax({
                    url: "{{ route('web.search.dish') }}",
                    data: {
                        query: q
                    },
                    method: 'GET',
                    success: function(items) {
                        $('#dish_suggestions').empty();
                        if (items && items.length) {
                            items.forEach(function(it) {
                                $('#dish_suggestions').append(
                                    `<a href="#" class="list-group-item list-group-item-action suggestion-item">${it.tenmonan}</a>`
                                );
                            });
                            $('#dish_suggestions').show();
                        } else {
                            $('#dish_suggestions').hide();
                        }
                    }
                });
            });

            // Click suggestion -> fill input + fetch recipe
            $(document).on('click', '.suggestion-item', function(e) {
                e.preventDefault();
                var name = $(this).text();
                $('#dish_name').val(name);
                $('#dish_suggestions').hide();

                // Show loader immediately
                showLoader();

                // fetch recipe
                $.ajax({
                    url: "{{ route('web.get.recipe') }}",
                    data: {
                        tenmonan: name
                    },
                    method: 'GET',
                    success: function(res) {
                        var text = res.congthuc ?? '';
                        showRecipe(text);
                    },
                    error: function() {
                        showRecipe(''); // show notfound
                    }
                });
            });

            // If user clicks "Gợi nguyên liệu" button, fetch based on current input
            $('#btnRecommend').on('click', function(e) {
                e.preventDefault();
                var name = $('#dish_name').val().trim();
                if (!name) {
                    // give quick UX hint
                    $('#dish_recipe').show();
                    // $('#dish_recipe_loader').hide();
                    $('#dish_recipe_content').hide();
                    $('#dish_recipe_notfound').text('Vui lòng nhập tên món để AI gợi ý.').show();
                    return;
                }

                showLoader();
                $.ajax({
                    url: "{{ route('web.get.recipe') }}",
                    data: {
                        tenmonan: name
                    },
                    method: 'GET',
                    success: function(res) {
                        var text = res.congthuc ?? '';
                        showRecipe(text);
                    },
                    error: function() {
                        showRecipe('');
                    }
                });
            });

            // click outside to hide suggestions (keep recipe visible)
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#dish_name, #dish_suggestions').length) {
                    $('#dish_suggestions').hide();
                }
            });

            // optional: when modal opens reset state
            $('#vegPrefForm').closest('.modal').on('show.bs.modal', function() {
                $('#dish_name').val('');
                $('#dish_suggestions').empty().hide();
                hideRecipe();
            });
        });
    </script>





    @yield('js')
    @yield('js_attr_search')
</body>

<!-- index-231:38-->

</html>
