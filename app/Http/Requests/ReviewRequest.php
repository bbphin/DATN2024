<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ReviewRequest extends FormRequest
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
            'content' => 'required',
            'rating' => 'max:5',
        ];
    }
    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'max' => ':attribute tối đa :max sao',
        ];
    }

    public function attributes()
    {
        return [
            'content' => 'Nội dung',
            'rating' => 'Số sao đánh giá',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = ApiResponse(false,Response::HTTP_BAD_REQUEST,$validator->errors(),null);
        throw (new ValidationException($validator,$response));
    }
}
