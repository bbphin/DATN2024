<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductRequest extends FormRequest
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
                'brand_id' => [Rule::exists('brands', 'id'),'required'],
                'color_id' => [Rule::exists('colors', 'id'),'required'],
                'size_id' => [Rule::exists('sizes', 'id'),'required'],
                'product_category_id' => [Rule::exists('product_categories', 'id'),'required'],
                'name' => 'required|unique:products',
                'slug' => 'unique:products',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|numeric|min:0',
                'image' => 'image'
            ];

        }

        if($this->isMethod('PUT')) {
            $id = $this->route('product');
            return [
                'brand_id' => [Rule::exists('brands', 'id'),'required'],
                'color_id' => [Rule::exists('colors', 'id'),'required'],
                'size_id' => [Rule::exists('sizes', 'id'),'required'],
                'product_category_id' => [Rule::exists('product_categories', 'id'),'required'],
                'name' => 'required|unique:products,name,' . $id,
                'slug' => 'unique:products,slug,' . $id,
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|numeric|min:0',
                'image' => 'image'
            ];
        }
        return [];
    }

    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'unique' => ':attribute đã tồn tại',
            'numeric' => ':attribute không hợp lệ',
            'min' => ':attribute tối thiểu :min giá trị',
            'image' => ':attribute không đúng định dạng',
            'exists' => ':attribute không tồn tại'
        ];
    }

    public function attributes()
    {
        return [
            'brand_id' => 'Tên thương hiệu',
            'color_id' => 'Màu',
            'size_id' => 'Kích thước',
            'product_category_id' => 'Danh mục sản phẩm',
            'name' => 'Tên sản phẩm',
            'price' => 'Giá sản phẩm',
            'quantity' => 'Số lượng sản phẩm',
            'image' => 'Ảnh sản phẩm',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = ApiResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors(), null);
        throw (new ValidationException($validator, $response));
    }
}
