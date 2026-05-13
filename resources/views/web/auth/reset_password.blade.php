@extends('layouts.master_user')

@section('content')
<div class="min-h-[80vh] bg-gray-50/50 flex items-center justify-center py-20 px-4">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black text-gray-900 mb-2 tracking-tighter">Đặt lại <span class="text-green-800">Mật khẩu</span></h1>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.3em]">Thiết lập mật khẩu mới cho tài khoản của bạn</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-green-900/5 border border-gray-100 p-10 relative overflow-hidden">
            <!-- Decorative element -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-full -mr-16 -mt-16 opacity-50"></div>

            <form action="{{ route('web.reset.password.post') }}" method="post" class="space-y-6 relative z-10">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                
                <!-- Email (Readonly) -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Email của bạn</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-5 flex items-center text-gray-400">
                            <i class="fa fa-envelope text-xs"></i>
                        </span>
                        <input type="email" name="email" value="{{ $email }}" readonly
                               class="w-full bg-gray-100 border-none rounded-2xl py-4 pl-12 pr-5 outline-none text-sm font-bold text-gray-500 cursor-not-allowed">
                    </div>
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Mật khẩu mới</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-5 flex items-center text-gray-400">
                            <i class="fa fa-lock text-xs"></i>
                        </span>
                        <input type="password" name="password" placeholder="••••••"
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-12 pr-5 focus:ring-2 focus:ring-green-900/10 transition-all outline-none text-sm font-bold @error('password') ring-2 ring-red-500/20 @enderror">
                    </div>
                    @error('password')
                        <p class="text-[10px] text-red-500 font-black uppercase tracking-wider ml-1 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Xác nhận mật khẩu</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-5 flex items-center text-gray-400">
                            <i class="fa fa-check-circle text-xs"></i>
                        </span>
                        <input type="password" name="password_confirm" placeholder="••••••"
                               class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-12 pr-5 focus:ring-2 focus:ring-green-900/10 transition-all outline-none text-sm font-bold @error('password_confirm') ring-2 ring-red-500/20 @enderror">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-green-900 text-white py-4 rounded-2xl font-black text-sm tracking-[0.2em] shadow-xl hover:bg-black transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                        ĐẶT LẠI MẬT KHẨU
                        <i class="fa fa-sync text-xs"></i>
                    </button>
                </div>
            </form>

            <div class="mt-10 text-center border-t border-gray-50 pt-8">
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">
                    Nhớ ra mật khẩu? 
                    <a href="{{ route('web.login') }}" class="text-green-800 hover:text-black transition-colors ml-2 font-black">Quay lại đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
