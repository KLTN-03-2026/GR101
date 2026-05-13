@extends('layouts.master_user')

@section('title', 'Liên hệ với chúng tôi')

@section('css')
<style>
    .contact-bg {
        background: #f8fafc;
        padding: 60px 0;
        min-height: 80vh;
    }
    .contact-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid #f1f5f9;
    }
    .contact-header {
        background: linear-gradient(135deg, #064e3b 0%, #10b981 100%);
        padding: 40px;
        color: white;
        text-align: center;
    }
    .contact-header h2 {
        font-weight: 800;
        font-size: 28px;
        margin-bottom: 10px;
        color: white;
    }
    .contact-header p {
        opacity: 0.9;
        font-size: 15px;
    }
    .contact-body {
        padding: 40px;
    }
    .form-group-premium {
        margin-bottom: 20px;
    }
    .form-group-premium label {
        display: block;
        font-size: 12px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }
    .input-premium {
        width: 100%;
        height: 50px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0 16px;
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
        transition: all 0.2s;
    }
    .input-premium:focus {
        border-color: #10b981;
        background: white;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        outline: none;
    }
    .textarea-premium {
        height: auto;
        padding: 16px;
        line-height: 1.5;
    }
    .btn-submit-premium {
        background: #1e293b;
        color: white !important;
        height: 54px;
        padding: 0 40px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 15px;
        border: none;
        transition: all 0.3s;
        cursor: pointer;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-submit-premium:hover {
        background: #10b981;
        transform: translateY(-2px);
    }
    .alert-modern {
        border-radius: 12px;
        padding: 16px 20px;
        font-weight: 600;
        margin-bottom: 25px;
        font-size: 14px;
    }
    .contact-info-box {
        background: #f0fdf4;
        border-radius: 16px;
        padding: 30px;
        height: 100%;
        border: 1px solid #dcfce7;
    }
    .contact-info-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 25px;
    }
    .contact-info-icon {
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #10b981;
        font-size: 18px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        flex-shrink: 0;
    }
    .contact-info-text h4 {
        font-size: 14px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 4px;
    }
    .contact-info-text p {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }
</style>
@endsection

@section('content')
<div class="contact-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="contact-card">
                    <div class="contact-header">
                        <h2>Liên Hệ Với Nông Sản Xanh</h2>
                        <p>Chúng tôi luôn lắng nghe và sẵn sàng hỗ trợ bạn bất cứ lúc nào.</p>
                    </div>

                    <div class="contact-body">
                        <div class="row">
                            <!-- Left: Form -->
                            <div class="col-md-7 mb-4 mb-md-0 pr-md-5">
                                @if(session('success'))
                                    <div class="alert alert-success alert-modern bg-green-50 text-green-700 border border-green-100 flex items-center gap-3">
                                        <i class="fa fa-check-circle text-xl"></i>
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if($errors->any())
                                    <div class="alert alert-danger alert-modern bg-red-50 text-red-700 border border-red-100">
                                        <ul class="mb-0 pl-4">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('web.contact.post') }}" method="POST">
                                    @csrf
                                    <div class="form-group-premium">
                                        <label>Họ và tên của bạn</label>
                                        <input type="text" name="name" class="input-premium" value="{{ auth()->check() ? auth()->user()->name : old('name') }}" required placeholder="Nhập họ tên...">
                                    </div>

                                    <div class="form-group-premium">
                                        <label>Địa chỉ Email</label>
                                        <input type="email" name="email" class="input-premium" value="{{ auth()->check() ? auth()->user()->email : old('email') }}" required placeholder="Nhập email...">
                                    </div>

                                    <div class="form-group-premium">
                                        <label>Chủ đề liên hệ</label>
                                        <input type="text" name="subject" class="input-premium" value="{{ old('subject') }}" required placeholder="Ví dụ: Lỗi đặt hàng, Góp ý sản phẩm...">
                                    </div>

                                    <div class="form-group-premium">
                                        <label>Nội dung chi tiết</label>
                                        <textarea name="content" rows="5" class="input-premium textarea-premium" required placeholder="Vui lòng mô tả chi tiết vấn đề của bạn...">{{ old('content') }}</textarea>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="btn-submit-premium">
                                            <i class="fa fa-paper-plane"></i> GỬI LIÊN HỆ NGAY
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Right: Info -->
                            <div class="col-md-5">
                                <div class="contact-info-box">
                                    <h3 class="text-lg font-black text-green-800 mb-6 border-b border-green-200 pb-3">Thông tin liên hệ</h3>
                                    
                                    <div class="contact-info-item">
                                        <div class="contact-info-icon"><i class="fa fa-map-marker-alt"></i></div>
                                        <div class="contact-info-text">
                                            <h4>Địa chỉ cửa hàng</h4>
                                            <p>123 Đường Nông Sản, Quận Xanh, TP.HCM</p>
                                        </div>
                                    </div>

                                    <div class="contact-info-item">
                                        <div class="contact-info-icon"><i class="fa fa-phone-alt"></i></div>
                                        <div class="contact-info-text">
                                            <h4>Đường dây nóng</h4>
                                            <p>0123.456.789 (Hỗ trợ 24/7)</p>
                                        </div>
                                    </div>

                                    <div class="contact-info-item">
                                        <div class="contact-info-icon"><i class="fa fa-envelope"></i></div>
                                        <div class="contact-info-text">
                                            <h4>Email hỗ trợ</h4>
                                            <p>{{ env('MAIL_FROM_ADDRESS', 'support@nongsanxanh.com') }}</p>
                                        </div>
                                    </div>

                                    <div class="contact-info-item">
                                        <div class="contact-info-icon"><i class="fa fa-clock"></i></div>
                                        <div class="contact-info-text">
                                            <h4>Giờ làm việc</h4>
                                            <p>Thứ 2 - Chủ nhật: 08:00 - 22:00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection