@extends('layouts.master_user')

@section('content')
<div class="min-h-[80vh] bg-gray-50/50 flex items-center justify-center py-20 px-4">
    <div class="max-w-md w-full">
        <!-- Logo/Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black text-gray-900 mb-2 tracking-tighter">Chào mừng <span class="text-green-800">Trở lại</span></h1>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.3em]">Đăng nhập vào tài khoản của bạn</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-green-900/5 border border-gray-100 p-10 relative overflow-hidden">
            <!-- Decorative element -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-full -mr-16 -mt-16 opacity-50"></div>

            <form action="{{ route('web.login.post') }}" method="post" class="space-y-6 relative z-10">
                @csrf
                
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

                <!-- Password -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center px-1">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Mật khẩu</label>
                        <a href="{{ route('web.forget.password.get') }}" class="text-[10px] font-black text-green-700 uppercase tracking-widest hover:text-black transition-colors">Quên mật khẩu?</a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-5 flex items-center text-gray-400">
                            <i class="fa fa-lock text-xs"></i>
                        </span>
                        <input type="password" name="password"
                               placeholder="••••••"
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-12 pr-5 focus:ring-2 focus:ring-green-900/10 transition-all outline-none text-sm font-bold @error('password') ring-2 ring-red-500/20 @enderror">
                    </div>
                    @error('password')
                        <p class="text-[10px] text-red-500 font-black uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center gap-3 px-1">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-gray-200 text-green-800 focus:ring-green-900/20">
                    <label for="remember" class="text-xs font-bold text-gray-400 uppercase tracking-widest cursor-pointer select-none">Ghi nhớ đăng nhập</label>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-green-900 text-white py-4 rounded-2xl font-black text-sm tracking-[0.2em] shadow-xl hover:bg-black transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                        ĐĂNG NHẬP
                        <i class="fa fa-sign-in-alt text-xs"></i>
                    </button>
                </div>

                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
                    <div class="relative flex justify-center text-[10px] uppercase tracking-[0.3em] font-black"><span class="bg-white px-4 text-gray-300">Hoặc</span></div>
                </div>

                @include('web.include.social_login')
            </form>

            <div class="mt-10 text-center border-t border-gray-50 pt-8">
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">
                    Chưa có tài khoản? 
                    <a href="{{ route('web.register') }}" class="text-green-800 hover:text-black transition-colors ml-2 font-black">Tham gia ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
