<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->isMethod('POST')) {
            return [
                'code' => 'required|unique:coupons',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'min_spend' => 'required',
                'max_discount_amount' => 'required',
                'total_usage_count' => 'required|numeric|min:1',
                'user_usage_count' => 'required|numeric|min:1',
            ];
        }
        if($this->isMethod('PUT')) {
            $id = $this->route('coupon');
            return [
                'code' => 'required|unique:coupons,code,'. $id,
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
                'min_spend' => 'required',
                'max_discount_amount' => 'required',
                'total_usage_count' => 'required|numeric|min:1',
                'user_usage_count' => 'required|numeric|min:1',
            ];
        }
        return [];
    }

    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'unique' => ':attribute đã tồn tại',
            'after' => ':attribute không được nhỏ hơn ngày bắt đầu',
            'numeric' => ':attribute không hợp lệ',
            'min' => ':attribute tối thiểu :min',
            'date' => ':attribute không đúng định dạng ngày tháng',
            'after_or_equal' => ':attribute không được chọn ngày trong quá khứ',
        ];
    }

    public function attributes()
    {
        return [
            'code' => 'Mã giảm giá',
            'start_date' => 'Ngày bắt đầu',
            'end_date' => 'Ngày kết thúc',
            'min_spend' => 'Số tiền tối thiểu từ đơn hàng',
            'max_discount_amount' => 'Số tiền tối đa giảm giá',
            'total_usage_count' => 'Số lượng mã giảm giá được dùng',
            'user_usage_count' => 'Số lượng người sử dụng',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = ApiResponse(false,Response::HTTP_BAD_REQUEST,$validator->errors(),null);
        throw (new ValidationException($validator,$response));
    }
}
