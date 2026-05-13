<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\RegisterRequest;
use App\Http\Requests\Web\UserLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showFormLogin(){
        return view('web.auth.login');
    }

    public function login(UserLoginRequest $request) {
        $email = $request->get('email');
        $password = $request->get('password');
        
        // Thêm kiểm tra tài khoản bị khóa
        $user = User::where('email', $email)->first();
        if ($user && $user->status == 0) {
            return redirect()->back()->with('error', 'Tài khoản của bạn đã bị khóa, vui lòng liên hệ Admin.');
        }

        $data = [
            'email' => $email,
            'password' => $password,
            'status' => 1 // Chỉ cho phép tài khoản đang hoạt động
        ];

        $isLogin = Auth::guard('web')->attempt($data, $request->get('remember'));

        if ($isLogin) {
            return redirect()->route('web.index')->with('success', 'Chào mừng bạn quay trở lại!');
        }

        return redirect()->back()->with('error', 'Tài khoản hoặc mật khẩu không chính xác!');
    }

    public function logout() {
        Auth::guard('web')->logout();
        return redirect()->route('web.login')->with('success', 'Đăng xuất thành công. Hẹn gặp lại bạn!');
    }

    public function showFormRegister() {
        return view('web.auth.register');
    }

    public function register(RegisterRequest $request) {
        try {
            $user = new User();
            $data = $request->only(['name', 'email', 'phone', 'password']);
            $data['password'] = Hash::make($data['password']);
            $user->fill($data);

            $user->save();
            return redirect()->route('web.login')->with('success', 'Đăng kí thành công');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }


}
