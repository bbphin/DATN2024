<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductCategoryRequest extends FormRequest
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
                'name' => 'required|unique:product_categories',
                'slug' => 'unique:product_categories',
                'category_id' => [
                    Rule::exists('categories', 'id'),
                    'required'
                ],
            ];
        }
        if($this->isMethod('PUT')) {
            $id = $this->route('product_category');
            return [
                'name' => 'required|unique:product_categories,name,' . $id,
                'slug' => 'unique:product_categories,slug,'.$id,
                'category_id' => [
                    Rule::exists('categories', 'id'),
                    'required'
                ],
            ];
        }
        return [];
    }

    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'unique' => ':attribute đã tồn tại',
            'exists' => ':attribute không tồn tại',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Danh mục sản phẩm',
            'category_id' => 'Mã danh mục',
            'slug' => 'Slug',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $response = ApiResponse(false,Response::HTTP_BAD_REQUEST,$validator->errors(),null);
        throw (new ValidationException($validator,$response));
    }
}
