@extends('layouts.master_user')

@section('content')
<div class="min-h-[80vh] bg-gray-50/50 flex items-center justify-center py-20 px-4">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black text-gray-900 mb-2 tracking-tighter">Khôi phục <span class="text-green-800">Mật khẩu</span></h1>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-[0.3em]">Nhập email để nhận liên kết lấy lại mật khẩu</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-green-900/5 border border-gray-100 p-10 relative overflow-hidden">
            <!-- Decorative element -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-green-50 rounded-full -mr-16 -mt-16 opacity-50"></div>

            <form action="{{ route('web.forget.password.post') }}" method="post" class="space-y-6 relative z-10">
                @csrf
                
                <!-- Email -->
                <div class="space-y-2">
                    <label class="text-xs font-black text-gray-400 uppercase tracking-widest ml-1">Địa chỉ Email</label>
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

                <div class="pt-4">
                    <button type="submit" class="w-full bg-green-900 text-white py-4 rounded-2xl font-black text-sm tracking-[0.2em] shadow-xl hover:bg-black transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3">
                        GỬI YÊU CẦU
                        <i class="fa fa-paper-plane text-xs"></i>
                    </button>
                </div>
            </form>

            <div class="mt-10 text-center border-t border-gray-50 pt-8">
                <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">
                    Quay lại 
                    <a href="{{ route('web.login') }}" class="text-green-800 hover:text-black transition-colors ml-2 font-black">Đăng nhập</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
