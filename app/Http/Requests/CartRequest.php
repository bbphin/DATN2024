<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CartRequest extends FormRequest
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
            'user_id' => [Rule::exists('users','id')],
            'product_id' => [Rule::exists('products','id')],
            'size_id' => [Rule::exists('sizes','id')],
            'color_id' => [Rule::exists('colors','id')],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages()
    {
        return [
            'exists' => ':attribute không tồn tại',
            'required' => ':attribute không được để trống',
            'min' => ':attribute tối thiểu :min',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'Người dùng',
            'product_id' => 'Sản phẩm',
            'size_id' => 'Kích thước',
            'color_id' => 'Màu sắc',
            'quantity' => 'Số lượng sản phẩm',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = ApiResponse(false,Response::HTTP_BAD_REQUEST,$validator->errors(),null);
        throw (new ValidationException($validator,$response));
    }
}
