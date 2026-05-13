<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:500',
            'old_password' => 'nullable|string|min:6|regex:/^\S*$/',
            'password' => 'nullable|string|min:6|confirmed|different:old_password|regex:/^\S*$/',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Họ tên không được để trống.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'address.required' => 'Địa chỉ không được để trống.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'password.different' => 'Mật khẩu mới không được trùng với mật khẩu cũ.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.regex' => 'Mật khẩu không được chứa khoảng trắng.',
            'old_password.regex' => 'Mật khẩu cũ không được chứa khoảng trắng.',
        ];
    }
}
