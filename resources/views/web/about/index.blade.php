@extends('layouts.master_user')
@section('content')
    <!-- Begin Li's Breadcrumb Area -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="breadcrumb-content">
                <ul>
                    <li><a href="index.html">Trang chủ</a></li>
                    <li class="active">Về chúng tôi</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Li's Breadcrumb Area End Here -->
    <!-- about wrapper start -->
    <div class="about-us-wrapper pt-60 pb-40">
        <div class="container">
            <div class="row">
                <!-- About Text Start -->
                <div class="col-lg-6 order-last order-lg-first">
                    <div class="about-text-wrap">
                        <h2><span>Sứ mệnh mang</span>Nông Sản Sạch Cho Bạn</h2>
                        <p>Nông Sản Xanh được ra đời từ niềm đam mê với nông nghiệp sạch và mong muốn bảo vệ sức khỏe cộng đồng. Chúng tôi tự hào là đơn vị tiên phong tại Đà Nẵng ứng dụng công nghệ AI để tuyển chọn những sản phẩm tốt nhất từ các trang trại VietGAP.</p>
                        <p>Với sự hỗ trợ từ các chuyên gia tại Đại học Duy Tân, mỗi sản phẩm tại cửa hàng đều trải qua quy trình kiểm định nghiêm ngặt, đảm bảo 3 tiêu chí: Tươi - Sạch - Nguồn gốc rõ ràng.</p>
                        <p>Chúng tôi không chỉ bán nông sản, chúng tôi trao gửi niềm tin và sức khỏe cho mỗi bữa cơm gia đình bạn. Hãy cùng Nông Sản Xanh xây dựng một lối sống lành mạnh từ hôm nay.</p>
                    </div>
                </div>
                <!-- About Text End -->
                <!-- About Image Start -->
                <div class="col-lg-5 col-md-10">
                    <div class="about-image-wrap">
                        <img class="img-full rounded" src="{{ asset('uploads/about/hero.png') }}" alt="Nông Sản Xanh Farm" />
                    </div>
                </div>
                <!-- About Image End -->
            </div>
        </div>
    </div>
    <!-- about wrapper end -->
    <!-- Begin Counterup Area -->
    <div class="counterup-area">
        <div class="container-fluid p-0">
            <div class="row no-gutters">
                <div class="col-lg-3 col-md-6">
                    <!-- Begin Limupa Counter Area -->
                    <div class="limupa-counter white-smoke-bg">
                        <div class="container">
                            <div class="counter-img">
                                <img src="{{ asset('theme/user/images/about-us/icon/1.png') }}" alt="">
                            </div>
                            <div class="counter-info">
                                <div class="counter-number">
                                    <h3 class="counter">5000</h3>
                                </div>
                                <div class="counter-text">
                                    <span>KHÁCH HÀNG TIN DÙNG</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- limupa Counter Area End Here -->
                </div>
                <div class="col-lg-3 col-md-6">
                    <!-- Begin limupa Counter Area -->
                    <div class="limupa-counter gray-bg">
                        <div class="counter-img">
                            <img src="{{ asset('theme/user/images/about-us/icon/2.png') }}" alt="">
                        </div>
                        <div class="counter-info">
                            <div class="counter-number">
                                <h3 class="counter">50</h3>
                            </div>
                            <div class="counter-text">
                                <span>TRANG TRẠI LIÊN KẾT</span>
                            </div>
                        </div>
                    </div>
                    <!-- limupa Counter Area End Here -->
                </div>
                <div class="col-lg-3 col-md-6">
                    <!-- Begin limupa Counter Area -->
                    <div class="limupa-counter white-smoke-bg">
                        <div class="counter-img">
                            <img src="{{ asset('theme/user/images/about-us/icon/3.png') }}" alt="">
                        </div>
                        <div class="counter-info">
                            <div class="counter-number">
                                <h3 class="counter">100</h3>
                            </div>
                            <div class="counter-text">
                                <span>% SẢN PHẨM SẠCH</span>
                            </div>
                        </div>
                    </div>
                    <!-- limupa Counter Area End Here -->
                </div>
                <div class="col-lg-3 col-md-6">
                    <!-- Begin limupa Counter Area -->
                    <div class="limupa-counter gray-bg">
                        <div class="counter-img">
                            <img src="{{ asset('theme/user/images/about-us/icon/4.png') }}" alt="">
                        </div>
                        <div class="counter-info">
                            <div class="counter-number">
                                <h3 class="counter">10</h3>
                            </div>
                            <div class="counter-text">
                                <span>NĂM KINH NGHIỆM</span>
                            </div>
                        </div>
                    </div>
                    <!-- limupa Counter Area End Here -->
                </div>
            </div>
        </div>
    </div>
    <!-- Counterup Area End Here -->
    <!-- team area wrapper start -->
    <div class="team-area pt-60 pt-sm-44">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="li-section-title capitalize mb-25">
                        <h2><span>Đội ngũ chuyên gia</span></h2>
                    </div>
                </div>
            </div> <!-- section title end -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team-member mb-60 mb-sm-30 mb-xs-30">
                        <div class="team-thumb">
                            <img class="img-full rounded-circle" src="{{ asset('uploads/about/team1.png') }}" alt="Expert">
                        </div>
                        <div class="team-content text-center">
                            <h3>Nguyễn Văn An</h3>
                            <p>Kỹ sư Nông nghiệp</p>
                            <a href="#">an.nguyen@nongsanxanh.vn</a>
                            <div class="team-social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-google-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div> <!-- end single team member -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team-member mb-60 mb-sm-30 mb-xs-30">
                        <div class="team-thumb">
                            <img class="img-full rounded-circle" src="{{ asset('uploads/about/team2.png') }}" alt="Expert">
                        </div>
                        <div class="team-content text-center">
                            <h3>Trần Thị Bình</h3>
                            <p>Chuyên gia Kiểm định</p>
                            <a href="#">binh.tran@nongsanxanh.vn</a>
                            <div class="team-social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-google-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div> <!-- end single team member -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team-member mb-30 mb-sm-60">
                        <div class="team-thumb">
                            <img src="{{ asset('theme/user/images/team/3.png') }}" alt="Our Team Member">
                        </div>
                        <div class="team-content text-center">
                            <h3>Lê Hoàng Nam</h3>
                            <p>Quản lý Vận hành</p>
                            <a href="#">nam.le@nongsanxanh.vn</a>
                            <div class="team-social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-google-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div> <!-- end single team member -->
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="team-member mb-30 mb-sm-60 mb-xs-60">
                        <div class="team-thumb">
                            <img src="{{ asset('theme/user/images/team/4.png') }}" alt="Our Team Member">
                        </div>
                        <div class="team-content text-center">
                            <h3>Phạm Minh Đức</h3>
                            <p>Chuyên viên Marketing</p>
                            <a href="#">duc.pham@nongsanxanh.vn</a>
                            <div class="team-social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-linkedin"></i></a>
                                <a href="#"><i class="fa fa-google-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div> <!-- end single team member -->
            </div>
        </div>
    </div>
    <!-- team area wrapper end -->
@endsection
