<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OrderRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:60',
            'phone' => 'required|numeric|digits_between:8,11',
            'address' => 'required',
            'payment_method' => 'required|in:COD,VNPAY',
            /**
             * @example
             */
            'note' => 'nullable|string|max:250'
        ];
    }

    public function messages()
    {
        return [
            'exists' => ':attribute không tồn tại',
            'in' => ':attribute không đúng giá trị cho phép',
            'max' => ':attribute tối đa :max kí tự',
            'required' => ':attribute không được để trống',
            'string' => ':attribute không đúng định dạng',
            'digits_between' => ':attribute phải có từ :min đến :max chữ số', 
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'Người dùng',
            'payment_method' => 'Phương thức thanh toán',
            'order_status' => 'Trạng thái đơn hàng',
            'name' => 'Tên người mua',
            'phone' => 'Số điện thoại người mua',
            'address' => 'Địa chỉ người mua',
            'note' => 'Ghi chú'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = ApiResponse(false,Response::HTTP_BAD_REQUEST,$validator->errors(),null);
        throw (new ValidationException($validator,$response));
    }
}
