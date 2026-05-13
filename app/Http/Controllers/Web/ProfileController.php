<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function showFormProfile(){
        $profileUser = Auth::guard('web')->user();

        if (empty($profileUser)) {
            abort(404);
        }

        return view('web.profile.index', compact('profileUser'));
    }

    public function profile(ProfileRequest $request, $id) {
        try {
            $user = User::find($id);
            
            // 1. Cập nhật thông tin cơ bản
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->address = $request->address;

            // 2. Xử lý đổi mật khẩu (nếu có nhập)
            if ($request->filled('password')) {
                // Kiểm tra mật khẩu cũ
                if (!Hash::check($request->old_password, $user->password)) {
                    return redirect()->back()->with('error', 'Mật khẩu cũ không chính xác.');
                }
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()->route('web.profile')->with('success', 'Cập nhật thông tin cá nhân thành công');
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau.');
        }
    }
}
