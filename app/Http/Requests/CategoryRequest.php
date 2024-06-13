<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CategoryRequest extends FormRequest
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
                'name' => 'required|unique:categories',
                'slug' => 'unique:categories',
            ];
        }

        if($this->isMethod('PUT')) {
            $id = $this->route('category');
            return [
                'name' => 'required|unique:categories,name,' .$id,
                'slug' => 'unique:categories,slug,' .$id,
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'unique' => ':attribute đã tồn tại',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên danh mục',
            'Slug' => 'Slug',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = ApiResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors(), null);
        throw (new ValidationException($validator, $response));
    }
}
