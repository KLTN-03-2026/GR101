@extends('layouts.master_user')

@section('css')
<style>
    /* Tổng thể trang */
    .profile-page-bg {
        background: #f1f5f9;
        padding: 40px 0;
        min-height: 100vh;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Thẻ chính */
    .profile-container {
        max-width: 700px;
        margin: 0 auto;
        background: white;
        border-radius: 28px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    /* Phần Header trang trí - Sửa lỗi chiếm diện tích */
    .profile-header-decor {
        height: 120px;
        background: linear-gradient(135deg, #064e3b 0%, #10b981 100%);
    }

    /* Avatar khu vực - Căn chỉnh lại */
    .profile-avatar-section {
        padding: 0 40px;
        margin-top: -45px;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 25px;
    }

    .avatar-box {
        width: 90px;
        height: 90px;
        background: white;
        border-radius: 24px;
        padding: 4px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }

    .avatar-inner {
        width: 100%;
        height: 100%;
        background: #f0fdf4;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 35px;
        color: #10b981;
    }

    .user-name-title h2 {
        font-size: 22px;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 5px 0;
    }

    /* Tabs */
    .nav-tabs-premium {
        border: none;
        padding: 0 40px;
        display: flex;
        gap: 25px;
        border-bottom: 1px solid #f1f5f9;
    }

    .nav-tabs-premium .nav-link {
        border: none;
        background: none;
        padding: 12px 0;
        font-weight: 700;
        font-size: 13px;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        cursor: pointer;
    }

    .nav-tabs-premium .nav-link.active {
        color: #10b981;
    }

    .nav-tabs-premium .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: #10b981;
    }

    /* Form Body */
    .profile-content-body {
        padding: 25px 40px;
    }

    .form-group-premium {
        margin-bottom: 18px;
    }

    .form-group-premium label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .input-premium {
        width: 100%;
        height: 46px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0 16px;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        transition: all 0.2s;
    }

    .input-premium:focus {
        border-color: #10b981;
        background: white;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.05);
        outline: none;
    }

    .textarea-premium {
        height: auto;
        padding: 12px 16px;
        line-height: 1.5;
    }

    /* Nút bấm */
    .btn-submit-premium {
        background: #1e293b;
        color: white !important;
        height: 48px;
        padding: 0 28px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 14px;
        border: none;
        transition: all 0.3s;
        cursor: pointer;
    }

    .btn-submit-premium:hover {
        background: #10b981;
        transform: translateY(-2px);
    }

    /* Alert */
    .alert-modern {
        border-radius: 16px;
        border: none;
        padding: 15px 20px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 25px;
    }
</style>
@endsection

@section('content')
<div class="profile-page-bg">
    <div class="container">
        <div class="profile-container">
            <div class="profile-header-decor"></div>

            <div class="profile-avatar-section">
                <div class="avatar-box">
                    <div class="avatar-inner">
                        <i class="fa fa-user"></i>
                    </div>
                </div>
                <div class="user-name-title">
                    <h2>{{ $profileUser->name }}</h2>
                    <span class="text-green-600 bg-green-50 px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider border border-green-100">Khách hàng</span>
                </div>
            </div>

            <ul class="nav nav-tabs-premium" id="profileTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#general">Thông tin hồ sơ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#security">Đổi mật khẩu</a>
                </li>
            </ul>

            <div class="profile-content-body">
                {{-- Hiển thị thông báo thành công --}}
                @if(session('success'))
                    <div class="alert alert-success alert-modern bg-green-50 text-green-700 flex items-center gap-3">
                        <i class="fa fa-check-circle text-lg"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Hiển thị thông báo lỗi (Bao gồm lỗi validation mật khẩu trùng) --}}
                @if(session('error') || $errors->any())
                    <div class="alert alert-danger alert-modern bg-red-50 text-red-700">
                        <div class="flex items-start gap-3">
                            <i class="fa fa-exclamation-circle text-lg mt-0.5"></i>
                            <div>
                                @if(session('error'))
                                    <p class="mb-1">{{ session('error') }}</p>
                                @endif
                                @if($errors->any())
                                    <ul class="mb-0 pl-0 list-none">
                                        @foreach ($errors->all() as $error)
                                            <li>• {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('web.profile.post', $profileUser->id) }}" method="post">
                    @csrf
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="general">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                                <div class="form-group-premium">
                                    <label>Họ và tên</label>
                                    <input type="text" name="name" class="input-premium" value="{{ old('name', $profileUser->name) }}" required>
                                </div>
                                <div class="form-group-premium">
                                    <label>Số điện thoại</label>
                                    <input type="text" name="phone" class="input-premium" value="{{ old('phone', $profileUser->phone) }}" required>
                                </div>
                                <div class="md:col-span-2 form-group-premium">
                                    <label>Email liên hệ</label>
                                    <input type="email" class="input-premium bg-gray-50 opacity-60" value="{{ $profileUser->email }}" readonly>
                                </div>
                                <div class="md:col-span-2 form-group-premium">
                                    <label>Địa chỉ mặc định</label>
                                    <textarea name="address" rows="3" class="input-premium textarea-premium" required>{{ old('address', $profileUser->address) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="security">
                            <div class="bg-amber-50 text-amber-800 p-4 rounded-xl mb-6 text-xs font-bold border border-amber-100 flex gap-3">
                                <i class="fa fa-shield-alt text-lg"></i>
                                <p>Để đổi mật khẩu, vui lòng nhập mật khẩu hiện tại và mật khẩu mới khác với mật khẩu cũ.</p>
                            </div>

                            <div class="space-y-1 max-w-md">
                                <div class="form-group-premium">
                                    <label>Mật khẩu cũ</label>
                                    <input type="password" name="old_password" class="input-premium" placeholder="••••••••">
                                </div>
                                <div class="form-group-premium">
                                    <label>Mật khẩu mới</label>
                                    <input type="password" name="password" class="input-premium" placeholder="••••••••">
                                </div>
                                <div class="form-group-premium">
                                    <label>Xác nhận mật khẩu</label>
                                    <input type="password" name="password_confirmation" class="input-premium" placeholder="••••••••">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="btn-submit-premium">
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
