@extends('layouts.master_user')

@section('content')
<div class="min-h-[80vh] bg-gray-50/50 flex items-center justify-center py-20 px-4">
    <div class="max-w-md w-full">
        <!-- Logo/Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black text-gray-900 mb-2 tracking-tighter">Tham gia với <span class="text-green-800">AgriAI</span></h1>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.3em]">Đăng ký tài khoản mới</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-green-900/5 border border-gray-100 p-10 relative overflow-hidden">
            <!-- Decorative element -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-full -mr-16 -mt-16 opacity-50"></div>

            <form action="{{ route('web.register.post') }}" method="post" class="space-y-6 relative z-10">
                @csrf
                
                <!-- Name -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Họ và tên</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-5 flex items-center text-gray-400">
                            <i class="fa fa-user text-xs"></i>
                        </span>
                        <input type="text" name="name" value="{{ old('name') }}"
                               placeholder="Nguyễn Văn A"
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-12 pr-5 focus:ring-2 focus:ring-green-900/10 transition-all outline-none text-sm font-bold @error('name') ring-2 ring-red-500/20 @enderror">
                    </div>
                    @error('name')
                        <p class="text-[10px] text-red-500 font-black uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-5 flex items-center text-gray-400">
                            <i class="fa fa-envelope text-xs"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="example@gmail.com"
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-12 pr-5 focus:ring-2 focus:ring-green-900/10 transition-all outline-none text-sm font-bold @error('email') ring-2 ring-red-500/20 @enderror">
                    </div>
                    @error('email')
                        <p class="text-[10px] text-red-500 font-black uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Số điện thoại</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-5 flex items-center text-gray-400">
                            <i class="fa fa-phone text-xs"></i>
                        </span>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="09xx xxx xxx"
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-12 pr-5 focus:ring-2 focus:ring-green-900/10 transition-all outline-none text-sm font-bold @error('phone') ring-2 ring-red-500/20 @enderror">
                    </div>
                    @error('phone')
                        <p class="text-[10px] text-red-500 font-black uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Mật khẩu</label>
                        <input type="password" name="password"
                               placeholder="••••••"
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 focus:ring-2 focus:ring-green-900/10 transition-all outline-none text-sm font-bold @error('password') ring-2 ring-red-500/20 @enderror">
                        @error('password')
                            <p class="text-[10px] text-red-500 font-black uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Xác nhận</label>
                        <input type="password" name="password_confirmation"
                               placeholder="••••••"
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 px-5 focus:ring-2 focus:ring-green-900/10 transition-all outline-none text-sm font-bold">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-green-900 text-white py-4 rounded-2xl font-black text-sm tracking-[0.2em] shadow-xl hover:bg-black transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                        ĐĂNG KÝ NGAY
                        <i class="fa fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </form>

            <div class="mt-10 text-center border-t border-gray-50 pt-8">
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">
                    Đã có tài khoản? 
                    <a href="{{ route('web.login') }}" class="text-green-800 hover:text-black transition-colors ml-2">Đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
