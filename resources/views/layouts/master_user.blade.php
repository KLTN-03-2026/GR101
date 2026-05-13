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
    
    @yield('css')

    <!-- Modernizr js -->
    <script src="{{ asset('theme/user/js/vendor/modernizr-2.8.3.min.js') }}"></script>

    <!-- noUiSlider -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>

    {{-- <link rel="stylesheet" href="{{ asset('lib/fontawesome/css/all.css') }}"> --}}

    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-green': '#166534',
                        'soft-green': '#f0fdf4',
                        'accent-yellow': '#facc15',
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', 'Helvetica', 'Arial', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body { 
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            color: #1c1e21; /* Facebook-like text color */
        }

        /* Improved Border Visibility */
        .border-gray-50 { border-color: #f0f2f5 !important; }
        .border-gray-100 { border-color: #ebedf0 !important; }
        .border-gray-200 { border-color: #dadde1 !important; }
        
        .shadow-sm { box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1) !important; }
        
        hr { border-top: 1px solid #dadde1 !important; }

        /* Hide number input arrows */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* --- Custom Floating Contact Buttons --- */
        .nsx-fab-wrapper {
            position: fixed;
            bottom: 100px; /* Đẩy cao lên để không đè vào nút chat */
            right: 20px; /* Chuyển sang bên phải theo yêu cầu */
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .nsx-fab-item {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .nsx-fab-item:hover {
            transform: scale(1.1) translateY(-5px);
            color: white;
        }

        .nsx-fab-item i {
            font-size: 22px;
        }

        /* Biện pháp mạnh: Ẩn tất cả các thành phần mạng xã hội từ bên thứ 3 */
        .tdc-social-channels, 
        .tdc-social-item,
        .tdc-clicker,
        .tdc-button-wrap,
        #zalo-chat-widget,
        [class*="tdc-social"], 
        [id*="tdc-social"] {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            height: 0 !important;
            width: 0 !important;
            pointer-events: none !important;
            position: absolute !important;
            left: -9999px !important;
        }

        /* Giữ lại nút Tư vấn chính của TDC (nếu cần) nhưng đảm bảo không chồng lấn */
        /* Nếu bạn muốn ẩn luôn cả nút Tư vấn, hãy thêm img[src*="tudongchat.com"] vào danh sách trên */

        /* Tooltip - Hiển thị bên TRÁI của nút (vào trong màn hình) */
        .nsx-fab-item::before {
            content: attr(data-tooltip);
            position: absolute;
            right: 65px; 
            background: #333;
            color: white;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            pointer-events: none;
            font-weight: 600;
        }

        .nsx-fab-item:hover::before {
            opacity: 1;
            visibility: visible;
            right: 60px;
        }

        /* Colors */
        .nsx-fab-zalo { background: #0084ff; }
        .nsx-fab-messenger { background: #00B2FF; background: linear-gradient(135deg, #00B2FF 0%, #006AFF 100%); }
        .nsx-fab-phone { background: #ee4d2d; animation: nsx-pulse 2s infinite; }
        .nsx-fab-top { background: #166534; opacity: 0; visibility: hidden; }
        .nsx-fab-top.show { opacity: 1; visibility: visible; }

        @keyframes nsx-pulse {
            0% { box-shadow: 0 0 0 0 rgba(238, 77, 45, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(238, 77, 45, 0); }
            100% { box-shadow: 0 0 0 0 rgba(238, 77, 45, 0); }
        }
    </style>

    <style>
        .carousel-inner img {
            width: 100%;
            height: 100%;
        }

        #vegPrefModal .ai-shop-modal-dialog {
            max-width: 920px;
            margin: 1rem auto;
        }

        #vegPrefModal .ai-shop-modal {
            max-height: calc(100vh - 2rem);
            overflow: hidden;
        }

        #vegPrefForm,
        #vegPrefModal .ai-shop-shell {
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 2rem);
            min-height: 0;
        }

        #vegPrefModal .ai-shop-header,
        #vegPrefModal .ai-shop-footer {
            flex: 0 0 auto;
        }

        #vegPrefModal .ai-shop-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
        }

        #vegPrefModal .ai-recipe-panel {
            min-height: 118px;
            align-items: stretch;
        }

        #vegPrefModal #dish_recipe_content {
            white-space: normal;
            border: 0;
            padding: 0;
            display: none;
            background: transparent;
            max-height: none;
            overflow: visible;
            border-radius: 0;
            box-shadow: none;
        }

        #vegPrefModal #dish_recipe_content > div {
            max-height: 175px;
            overflow-y: auto;
        }

        #vegPrefModal .ai-results {
            max-height: none;
            overflow: visible;
        }

        #vegPrefModal .veg-card img {
            height: 118px !important;
            object-fit: contain !important;
            background: #f9fafb;
        }

        #vegPrefModal .ai-shop-footer button {
            min-height: 54px;
            white-space: normal;
            line-height: 1.25;
        }

        @media (max-width: 767.98px) {
            #vegPrefModal .ai-shop-modal-dialog {
                margin: .5rem;
            }

            #vegPrefModal .ai-shop-modal,
            #vegPrefForm,
            #vegPrefModal .ai-shop-shell {
                max-height: calc(100vh - 1rem);
            }

            #vegPrefModal .ai-shop-body {
                padding: 1rem !important;
            }

            #vegPrefModal .ai-shop-footer {
                padding: .75rem !important;
                flex-direction: column;
            }

            #vegPrefModal .ai-shop-footer button {
                width: 100%;
            }
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
        <!-- Begin Modern Header Area -->
        <header class="font-sans bg-white">

            <!-- Main Header -->
            <div class="py-2.5 px-4 md:px-10 border-b border-gray-100">
                <div class="max-w-[1500px] mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
                    <!-- Logo -->
                    <a href="{{ route('web.index') }}" class="flex items-center gap-3 shrink-0 group">
                        <div class="w-14 h-14 bg-primary-green rounded-2xl flex items-center justify-center overflow-hidden shadow-lg group-hover:rotate-6 transition-transform duration-300">
                             <i class="fa fa-leaf text-white text-2xl"></i>
                        </div>
                        <div class="hidden sm:block">
                            <h1 class="text-3xl font-extrabold text-green-900 leading-none tracking-tight">AgriAI</h1>
                            <p class="text-[10px] uppercase tracking-[0.2em] text-green-600 font-bold mt-1">Nông Sản Mỹ Thuật</p>
                        </div>
                    </a>

                    <!-- Search -->
                    <div class="flex-1 w-full max-w-2xl relative group px-4">
                        <form action="{{ route('web.search') }}" class="relative group">
                            <input 
                                type="text" 
                                name="search"
                                id="header-search-input"
                                value="{{ request()->get('search') ?? '' }}"
                                placeholder="Hôm nay bạn cần mua gì?"
                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl py-4 px-8 outline-none focus:bg-white focus:border-green-600/20 focus:shadow-[0_20px_50px_rgba(22,101,52,0.1)] transition-all duration-300 text-gray-700 placeholder:text-gray-400 shadow-inner font-medium"
                                autocomplete="off"
                            />
                            <div id="search-suggestions" class="absolute left-0 right-0 top-full mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden hidden z-[1001]">
                                <!-- Suggestions will be rendered here -->
                            </div>
                            <button type="submit" class="absolute right-2 top-2 bottom-2 px-8 bg-green-900 text-white rounded-[1.25rem] hover:bg-black transition-all flex items-center justify-center shadow-lg active:scale-95 group-hover:shadow-green-900/20">
                                <i class="fa fa-search text-sm"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-4">
                        <!-- Cart -->
                        <a href="#" id="cart-icon" class="relative group flex items-center gap-4 bg-white border border-gray-100 p-1.5 pr-8 rounded-full hover:shadow-[0_20px_50px_rgba(0,0,0,0.1)] hover:border-green-100 transition-all duration-500 active:scale-95">
                            <div class="w-12 h-12 bg-green-900 rounded-full flex items-center justify-center text-white shadow-lg group-hover:rotate-12 transition-transform duration-300">
                                <i class="fa fa-shopping-basket text-lg"></i>
                                <span class="absolute -top-1 -right-1 bg-yellow-400 text-green-900 text-[10px] font-black w-6 h-6 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                                    @if (\Illuminate\Support\Facades\Auth::guard('web')->check())
                                        {{ \Illuminate\Support\Facades\Auth::guard('web')->user()->countListProductInCart() }}
                                    @else
                                        0
                                    @endif
                                </span>
                            </div>
                            <div class="hidden lg:block leading-tight">
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Giỏ hàng</span>
                                <span class="block text-sm font-black text-gray-900 tracking-tight">Thanh toán</span>
                            </div>
                        </a>

                        <!-- User Account -->
                        @if (\Illuminate\Support\Facades\Auth::guard('web')->check())
                            <div class="group relative">
                                <button class="flex items-center gap-3 bg-white border border-gray-100 p-1 pr-6 rounded-full hover:shadow-xl hover:border-green-100 transition-all duration-300 active:scale-95">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-600 shadow-inner group-hover:bg-primary-green group-hover:text-white transition-all duration-300">
                                        <i class="fa fa-user text-sm"></i>
                                    </div>
                                    <div class="hidden lg:block text-left leading-tight">
                                        <span class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Xin chào,</span>
                                        <span class="block text-sm font-black text-gray-900 tracking-tight">{{ \Illuminate\Support\Facades\Auth::guard('web')->user()->name }}</span>
                                    </div>
                                </button>
                                
                                <!-- Dropdown Menu (Simplified & Reliable) -->
                                <div class="absolute right-0 top-full pt-1 hidden group-hover:block z-[9999]">
                                    <div class="bg-white shadow-[0_10px_40px_rgba(0,0,0,0.15)] border border-gray-100 rounded-[1.5rem] w-60 overflow-hidden">
                                        <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-100">
                                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Tài khoản</p>
                                            <p class="text-sm font-bold text-gray-900 truncate">{{ \Illuminate\Support\Facades\Auth::guard('web')->user()->email }}</p>
                                        </div>
                                        <div class="p-2">
                                            <a href="{{ route('web.profile') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-soft-green hover:text-green-700 rounded-xl transition-colors font-medium">
                                                <i class="fa fa-user-circle text-lg opacity-40"></i> 
                                                Thông tin cá nhân
                                            </a>
                                            <a href="{{ route('web.list_order_of_user') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-soft-green hover:text-green-700 rounded-xl transition-colors font-medium">
                                                <i class="fa fa-shopping-bag text-lg opacity-40"></i> 
                                                Đơn hàng của tôi
                                            </a>
                                            <div class="border-t border-gray-50 my-1 mx-2"></div>
                                            <a href="{{ route('web.logout') }}" class="flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 rounded-xl transition-colors font-black">
                                                <i class="fa fa-sign-out-alt text-lg opacity-80"></i> 
                                                Đăng xuất
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-3 pl-2">
                                <a href="{{ route('web.login') }}" class="text-sm font-black text-gray-400 hover:text-green-600 transition-colors uppercase tracking-widest">Login</a>
                                <a href="{{ route('web.register') }}" class="bg-green-900 text-white px-8 py-3.5 rounded-full font-black text-xs tracking-widest hover:bg-black transition-all shadow-xl active:scale-95 uppercase">Join Now</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sticky Navigation -->
            <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-[1000] overflow-x-auto whitespace-nowrap hidden lg:block shadow-sm">
                <div class="max-w-7xl mx-auto px-8 py-4 flex justify-center gap-12">
                    <a href="{{ route('web.index') }}" class="text-sm font-black tracking-widest transition-all relative py-1 {{ request()->routeIs('web.index') ? 'text-green-600 after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:w-full after:h-1 after:bg-green-600 after:rounded-full' : 'text-gray-500 hover:text-green-600' }}">TRANG CHỦ</a>
                    <a href="{{ route('web.about') }}" class="text-sm font-black tracking-widest transition-all relative py-1 {{ request()->routeIs('web.about') ? 'text-green-600 after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:w-full after:h-1 after:bg-green-600 after:rounded-full' : 'text-gray-500 hover:text-green-600' }}">VỀ CHÚNG TÔI</a>
                    <a href="{{ route('web.contact') }}" class="text-sm font-black tracking-widest transition-all relative py-1 {{ request()->routeIs('web.contact') ? 'text-green-600 after:content-[\'\'] after:absolute after:bottom-0 after:left-0 after:w-full after:h-1 after:bg-green-600 after:rounded-full' : 'text-gray-500 hover:text-green-600' }}">LIÊN HỆ</a>
                    <a href="#" data-toggle="modal" data-target="#vegPrefModal" class="text-sm font-black tracking-widest text-green-700 bg-soft-green px-4 py-1 rounded-full hover:bg-green-100 transition-all flex items-center gap-2">
                        <span class="animate-pulse text-lg"></span> AI GỢI Ý MUA SẮM
                    </a>
                </div>
            </nav>
        </header>
        <!-- Header Area End Here -->


    <!-- Premium AI Recommendation Modal -->
    <div class="modal fade" id="vegPrefModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg ai-shop-modal-dialog" role="document">
            <div class="modal-content rounded-[2.5rem] border-none shadow-2xl bg-white ai-shop-modal">
                <form id="vegPrefForm" method="POST" action="{{ route('web.ai.recommend') }}">
                    @csrf
                    <div class="relative ai-shop-shell">
                        <!-- Header with Background Gradient -->
                        <div class="bg-gradient-to-r from-green-900 to-green-800 p-6 text-white relative ai-shop-header">
                            <button type="button" class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors" data-dismiss="modal">
                                <i class="fa fa-times text-xl"></i>
                            </button>
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl">🤖</div>
                                <div>
                                    <h5 class="text-xl font-black tracking-tight text-white mb-0">AI Gợi ý mua sắm</h5>
                                    <p class="text-white/60 text-[10px] font-bold uppercase tracking-widest mt-1">Trợ lý đầu bếp thông minh</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 ai-shop-body custom-scrollbar">
                            <div class="mb-6">
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Bạn muốn nấu món gì?</label>
                                <div class="relative group">
                                    <div class="absolute left-5 top-1/2 -translate-y-1/2 text-green-900/30 group-focus-within:text-green-900 transition-colors">
                                        <i class="fa fa-utensils"></i>
                                    </div>
                                    <input type="text" id="dish_name" name="dish_name" 
                                           class="w-full bg-gray-50 border-2 border-gray-100 focus:border-green-900 focus:bg-white rounded-2xl py-4 pl-12 pr-6 text-gray-900 font-bold placeholder:text-gray-300 outline-none transition-all"
                                           placeholder="Ví dụ: Cá chiên, bò kho..." autocomplete="off">
                                    
                                    <div id="dish_suggestions" class="absolute top-full left-0 w-full bg-white mt-2 rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-[1000] hidden">
                                        <!-- Suggestions will be injected here -->
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden recipe field -->
                            <input type="hidden" name="dish_recipe" id="dish_recipe_hidden">

                            <!-- Loader / Content Area -->
                            <div class="flex flex-col items-center justify-center text-center p-4 bg-gray-50 rounded-3xl border border-gray-100 border-dashed transition-all ai-recipe-panel" id="dish_recipe_area">
                                <div id="dish_recipe_loader" class="flex flex-col items-center gap-4">
                                    <div class="w-10 h-10 border-4 border-green-900/10 border-t-green-900 rounded-full animate-spin"></div>
                                    <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest">AI đang sẵn sàng...</p>
                                </div>
                                
                                <div id="dish_recipe_content" class="hidden w-full text-left">
                                    <p class="text-[10px] font-black text-green-900 uppercase tracking-widest mb-3">Công thức tìm thấy:</p>
                                    <div class="text-gray-600 text-sm leading-relaxed pr-4 custom-scrollbar whitespace-pre-wrap font-medium"></div>
                                </div>

                                <div id="dish_recipe_notfound" class="hidden flex flex-col items-center gap-2">
                                    <i class="fa fa-info-circle text-gray-300 text-xl"></i>
                                    <p class="text-gray-400 text-[10px] font-bold italic">AI sẽ giúp bạn tìm công thức phù hợp nhất!</p>
                                </div>
                            </div>

                            <!-- AI Suggested Products Container - Added scroll if too many products -->
                            <div id="vegResults" class="row mt-5 ai-results"></div>
                        </div>

                        <div class="p-5 bg-gray-50/95 flex gap-3 border-t border-gray-100 ai-shop-footer">
                            <button type="button" id="btnRecommend" class="flex-[2] bg-green-900 text-white py-4 rounded-xl font-black text-[11px] tracking-widest uppercase shadow-lg hover:bg-black transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                                Khám phá nguyên liệu <i class="fa fa-arrow-right"></i>
                            </button>
                            <button type="button" id="btnAddAllToCart" class="hidden flex-[2] bg-yellow-400 text-green-900 py-4 rounded-xl font-black text-[11px] tracking-widest uppercase shadow-lg hover:bg-yellow-500 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                                Thêm tất cả vào giỏ <i class="fa fa-cart-plus"></i>
                            </button>
                            <button type="button" class="flex-1 px-6 bg-white border border-gray-200 text-gray-500 py-4 rounded-xl font-black text-[11px] tracking-widest uppercase hover:bg-gray-50 transition-all" data-dismiss="modal">
                                Đóng
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

                </div>
            </div>
        </div>

        {{-- Styles nhỏ cho z-index & card --}}
        <style>
            .header-sticky { z-index: 100; }
            .modal-backdrop { z-index: 1050; }
            .modal { z-index: 1060; }

            .custom-scrollbar::-webkit-scrollbar { width: 4px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #14532d; border-radius: 10px; }

            .suggestion-item {
                padding: 1rem 1.5rem;
                font-weight: 600;
                font-size: 0.875rem;
                color: #1f2937;
                border-bottom: 1px solid #f3f4f6;
                transition: all 0.2s;
                display: block;
            }
            .suggestion-item:hover {
                background-color: #f0fdf4;
                color: #14532d;
                padding-left: 2rem;
            }
            .suggestion-item:last-child { border-bottom: none; }
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
                                        **Đại học Duy Tân - Đà Nẵng
                                    </li>
                                    <li>
                                        <span>Hotline Đặt hàng: </span>
                                        <a href="tel:+84584246834">(+84) 123456789</a>
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
        <!-- Nông Sản Xanh Floating Buttons (Right Side) -->
        <div class="nsx-fab-wrapper">
            <!-- Facebook -->
            <a href="https://www.facebook.com/jeno19cc" target="_blank" class="nsx-fab-item nsx-fab-messenger" data-tooltip="Facebook Cá nhân">
                <i class="fa fa-facebook"></i>
            </a>

            <!-- Zalo -->
            <a href="https://zalo.me/0344192141" target="_blank" class="nsx-fab-item nsx-fab-zalo" data-tooltip="Zalo: 0344.192.141">
                <i class="fa fa-comment"></i>
            </a>

            <!-- Hotline -->
            <a href="tel:0344192141" class="nsx-fab-item nsx-fab-phone" data-tooltip="Gọi Hotline: 0344.192.141">
                <i class="fa fa-phone"></i>
            </a>

            <!-- Back to Top -->
            <a href="javascript:void(0)" class="nsx-fab-item nsx-fab-top" id="nsx-back-to-top" data-tooltip="Lên đầu trang">
                <i class="fa fa-angle-up"></i>
            </a>
        </div>

        <!-- TuDongChat Integration -->
        <script src="https://app.tudongchat.com/js/chatbox.js"></script>
        <script>
            const tudong_chatbox = new TuDongChat('5mTKohbMB-i-PcTxM_iHg')
            tudong_chatbox.initial()

            // Xử lý triệt để việc ẩn các nút mạng xã hội trùng lặp sau khi TuDongChat load
            window.addEventListener('load', function() {
                const hideTrashButtons = () => {
                    const selectors = [
                        '.tdc-social-channels',
                        '.tdc-social-item',
                        '.tdc-clicker',
                        '.tdc-button-wrap',
                        '#zalo-chat-widget'
                    ];
                    selectors.forEach(s => {
                        const els = document.querySelectorAll(s);
                        els.forEach(el => {
                            el.style.setProperty('display', 'none', 'important');
                            el.style.setProperty('visibility', 'hidden', 'important');
                            el.remove(); // Xóa luôn khỏi DOM cho chắc chắn
                        });
                    });
                };

                // Chạy ngay và chạy lại sau 2s, 5s để đảm bảo dọn sạch các nút chèn muộn
                hideTrashButtons();
                setTimeout(hideTrashButtons, 2000);
                setTimeout(hideTrashButtons, 5000);
                
                // Lắng nghe sự thay đổi của DOM để xóa nếu nó tự hiện lại
                const observer = new MutationObserver(hideTrashButtons);
                observer.observe(document.body, { childList: true, subtree: true });
            });
        </script>

        <script>
            // Back to top functionality
            window.onscroll = function() {
                if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                    document.getElementById("nsx-back-to-top").classList.add("show");
                } else {
                    document.getElementById("nsx-back-to-top").classList.remove("show");
                }
            };

            document.getElementById("nsx-back-to-top").onclick = function() {
                window.scrollTo({top: 0, behavior: 'smooth'});
            };
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

            function escapeHtml(value) {
                return $('<div>').text(value || '').html();
            }

            function showLoader() {
                $('#dish_recipe_content').hide();
                $('#dish_recipe_notfound').hide();
                $('#dish_recipe_loader').show();
                $('#dish_recipe_area').addClass('border-dashed bg-gray-50').removeClass('bg-white');
            }

            function showRecipe(text, persistInput) {
                $('#dish_recipe_loader').hide();
                if (persistInput !== false) {
                    $('#dish_recipe_hidden').val(text || '');
                }

                if (text && text.trim().length) {
                    $('#dish_recipe_content div').html(text);
                    $('#dish_recipe_content').removeClass('hidden').show();
                    $('#dish_recipe_notfound').hide();
                    $('#dish_recipe_area').removeClass('border-dashed bg-gray-50').addClass('bg-white');
                } else {
                    $('#dish_recipe_content').hide();
                    $('#dish_recipe_notfound').removeClass('hidden').show();
                    $('#dish_recipe_area').addClass('border-dashed bg-gray-50').removeClass('bg-white');
                }
            }

            function hideRecipe() {
                $('#dish_recipe_loader').hide();
                $('#dish_recipe_content').hide();
                $('#dish_recipe_notfound').hide();
                $('#dish_recipe_hidden').val('');
                $('#dish_recipe_area').addClass('border-dashed bg-gray-50').removeClass('bg-white');
            }

            function buildRecipeHtml(res) {
                var html = '';

                if (res.mo_ta) {
                    html += '<p class="mb-3">' + escapeHtml(res.mo_ta) + '</p>';
                }

                if (res.nguyen_lieu && res.nguyen_lieu.length) {
                    html += '<p class="text-[10px] font-black text-green-900 uppercase tracking-widest mb-2">Nguyen lieu:</p><ul class="pl-4 mb-3">';
                    html += res.nguyen_lieu.map(function(item) {
                        return '<li class="mb-1">' + escapeHtml(item) + '</li>';
                    }).join('');
                    html += '</ul>';
                }

                if (res.cach_lam && res.cach_lam.length) {
                    html += '<p class="text-[10px] font-black text-green-900 uppercase tracking-widest mb-2">Cach lam:</p><ol class="pl-4 mb-0">';
                    html += res.cach_lam.map(function(step) {
                        return '<li class="mb-1">' + escapeHtml(step) + '</li>';
                    }).join('');
                    html += '</ol>';
                }

                if (!html && res.congthuc) {
                    html = escapeHtml(res.congthuc);
                }

                return html;
            }

            // mỗi lần mở modal, xoá kết quả cũ (tùy chọn)
            $('#vegPrefModal').on('shown.bs.modal', function() {
                $results.empty();
            });

            // AI Recommendation Logic
            $('#btnRecommend').on('click', function(e) {
                e.preventDefault();
                var dish_name = $('#dish_name').val() ? $('#dish_name').val().trim() : '';
                
                if (!dish_name) {
                    $results.html('<div class="col-12 text-warning">Vui lòng nhập tên món ăn để AI gợi ý.</div>');
                    return;
                }

                $results.html('<div class="col-12 text-center py-4"><i class="fa fa-spinner fa-spin fa-2x mb-2"></i><p>AI đang phân tích món ăn và tìm sản phẩm phù hợp...</p></div>');
                showLoader();

                var congthuc = $('#dish_recipe_hidden').val() ? $('#dish_recipe_hidden').val().trim() : '';

                $.ajax({
                    url: "{{ route('web.ai.recommend') }}",
                    type: "POST",
                    data: JSON.stringify({
                        tenmon: dish_name,
                        congthuc: congthuc
                    }),
                    contentType: "application/json; charset=utf-8",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || $form.find('input[name="_token"]').val()
                    },
                    success: function(res) {
                        // Hiển thị công thức
                        showRecipe(buildRecipeHtml(res), false);

                        // Hiển thị sản phẩm gợi ý
                        if (!res.items || !res.items.length) {
                            $results.html(
                                '<div class="col-12 text-muted text-center py-4">AI không tìm thấy sản phẩm nào khớp hoàn toàn. Bạn có thể thử tìm thủ công hoặc nhập tên món khác.</div>'
                            );
                            $('#btnAddAllToCart').addClass('hidden');
                            return;
                        }

                        // Show 'Add all' button if products found
                        $('#btnAddAllToCart').removeClass('hidden').data('product-ids', res.items.map(i => i.id));

                        var html = '<div class="col-12 mb-3"><h6 class="font-weight-bold text-success"><i class="fa fa-shopping-basket"></i> Sản phẩm nên mua:</h6></div>';
                        html += res.items.map(function(item) {
                            return `
                                <div class="col-6 col-md-4 mb-4">
                                    <div class="veg-card h-100 shadow-sm border-0 rounded-lg overflow-hidden">
                                        <div class="position-relative">
                                            <img src="${escapeHtml(item.thumbnail)}" class="card-img-top w-100" style="height:140px; object-fit:cover" alt="${escapeHtml(item.name)}">
                                            <span class="position-absolute top-0 right-0 badge badge-success m-2">Gợi ý</span>
                                        </div>
                                        <div class="p-3">
                                            <h6 class="veg-title mb-1 text-truncate">${escapeHtml(item.name)}</h6>
                                            <p class="text-muted mb-1 small">${escapeHtml(item.category_name || 'Nguyen lieu')}</p>
                                            <p class="text-danger font-weight-bold mb-2 small">${new Intl.NumberFormat('vi-VN').format(item.price)} ₫</p>
                                            <a href="${escapeHtml(item.url)}" class="btn btn-outline-success btn-sm btn-block rounded-pill">Mua ngay</a>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                        $results.html(html);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText || xhr.statusText);
                        $results.html(
                            '<div class="col-12 text-danger text-center py-4">Rất tiếc, AI đang bận hoặc gặp lỗi. Vui lòng thử lại sau vài phút.</div>'
                        );
                        $('#btnAddAllToCart').addClass('hidden');
                        hideRecipe();
                    }
                });
            });

            // Handle Add All to Cart
            $('#btnAddAllToCart').on('click', function() {
                var productIds = $(this).data('product-ids');
                if (!productIds || !productIds.length) return;

                $.LoadingOverlay('show');
                $.ajax({
                    url: "{{ route('web.cart.bulk_add') }}",
                    method: 'POST',
                    data: {
                        product_ids: productIds,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        $.LoadingOverlay('hide');
                        if (res.success) {
                            $('.cart-item-count').text(res.qty);
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: res.message,
                                showConfirmButton: true
                            }).then(() => {
                                $('#vegPrefModal').modal('hide');
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: res.message
                            });
                        }
                    },
                    error: function() {
                        $.LoadingOverlay('hide');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Thông báo',
                            text: 'Vui lòng đăng nhập để thực hiện tính năng này'
                        });
                    }
                });
            });
        })(jQuery);
    </script>

    <script>
        $(function() {
            // helper: show loader area
            function showLoader() {
                $('#dish_recipe_content').hide();
                $('#dish_recipe_notfound').hide();
                $('#dish_recipe_loader').show();
                $('#dish_recipe_area').addClass('border-dashed bg-gray-50').removeClass('bg-white');
            }

            function showRecipe(text) {
                $('#dish_recipe_loader').hide();
                $('#dish_recipe_hidden').val(text);

                if (text && text.trim().length) {
                    $('#dish_recipe_content div').text(text);
                    $('#dish_recipe_content').removeClass('hidden').show();
                    $('#dish_recipe_notfound').hide();
                    $('#dish_recipe_area').removeClass('border-dashed bg-gray-50').addClass('bg-white');
                } else {
                    $('#dish_recipe_content').hide();
                    $('#dish_recipe_notfound').removeClass('hidden').show();
                    $('#dish_recipe_area').addClass('border-dashed bg-gray-50').removeClass('bg-white');
                }
            }


            function hideRecipe() {
                $('#dish_recipe_loader').hide();
                $('#dish_recipe_content').hide();
                $('#dish_recipe_notfound').hide();
                $('#dish_recipe_area').addClass('border-dashed bg-gray-50').removeClass('bg-white');
            }

            // Autocomplete: fetch suggestions
            var ajaxSuggest;
            $('#dish_name').on('keyup', function() {
                var q = $(this).val().trim();
                $('#dish_recipe_hidden').val('');
                $('#vegResults').empty();
                $('#btnAddAllToCart').addClass('hidden');

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

            // Click suggestion -> fill input + fetch recipe -> Trigger AI Recommend
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
                        // Tự động kích hoạt gợi ý sản phẩm sau khi có công thức
                        $('#btnRecommend').trigger('click');
                    },
                    error: function() {
                        showRecipe(''); // show notfound
                        $('#btnRecommend').trigger('click');
                    }
                });
            });

            // (Events consolidated above for #btnRecommend)

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
    <script>
        $(document).ready(function() {
            let searchTimer;
            $('#header-search-input').on('input', function() {
                const query = $(this).val();
                clearTimeout(searchTimer);
                
                if (query.length < 2) {
                    $('#search-suggestions').addClass('hidden');
                    return;
                }

                searchTimer = setTimeout(function() {
                    $.ajax({
                        url: '{{ route("web.search.autocomplete") }}',
                        data: { query: query },
                        success: function(res) {
                            let html = '';
                            if (res.length > 0) {
                                res.forEach(item => {
                                    html += `
                                        <a href="${item.url}" class="flex items-center gap-4 p-4 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0">
                                            <img src="${item.image}" class="w-10 h-10 rounded-lg object-cover">
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-gray-900">${item.name}</p>
                                                <p class="text-[10px] font-black text-green-700">${item.price}</p>
                                            </div>
                                        </a>
                                    `;
                                });
                                $('#search-suggestions').html(html).removeClass('hidden');
                            } else {
                                $('#search-suggestions').addClass('hidden');
                            }
                        }
                    });
                }, 300);
            });

            // Close suggestions when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.group').length) {
                    $('#search-suggestions').addClass('hidden');
                }
            });
        });

        function quickAddToCart(event, productId) {
            event.preventDefault();
            event.stopPropagation();
            
            $.LoadingOverlay('show');
            $.ajax({
                url: '{{ route("web.cart.add") }}',
                method: 'get',
                data: {
                    product_id: productId,
                    quantity: 1
                },
                success: function(res) {
                    $.LoadingOverlay('hide');
                    if (res.success) {
                        $('.cart-item-count').text(res.data.qty);
                        Swal.fire({
                            icon: 'success',
                            title: 'Đã thêm vào giỏ!',
                            text: res.data.message,
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true,
                            position: 'top-end'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: res.data.message
                        });
                    }
                },
                error: function() {
                    $.LoadingOverlay('hide');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Thông báo',
                        text: 'Vui lòng đăng nhập để thực hiện tính năng này'
                    });
                }
            });
        }
    </script>

    @yield('js')
</body>

<!-- index-231:38-->

</html>
