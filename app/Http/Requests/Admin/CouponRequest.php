<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
        $rules = [
            'name'         => 'required|unique:coupons,name,' . ($this->route('coupon')?->id ?? ''),
            'type'         => 'required|in:price,percent',
            'discount'     => 'required|numeric|min:0',
            'discount_max' => 'numeric|nullable|min:0',
            'number_use'   => 'integer|min:0|nullable',
            'start'        => 'required|date',
            'end'          => 'required|date|after_or_equal:start',
        ];

        // If discount type is percent, cap at 100
        if ($this->input('type') === 'percent') {
            $rules['discount'] .= '|max:100';
        }

        return $rules;
    }

    public function messages() {
        return [
            'end.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu'
        ];
    }
}
