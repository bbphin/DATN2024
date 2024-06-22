<?php

namespace App\Http\Requests\Subscriber;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
class AddSubscriberFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            /**
             * @example Nguyễn Thành Sơn
             */
            'fullname' => 'required|string|max:100',
            /**
             * @example nguyenthanhsont123@gmail.com
             */
            'email' => 'required|email|unique:subscribers,email',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            '*.required' => 'Vui lòng nhập :attribute.',
            '*.email' => 'Vui lòng nhập đúng định dạng :attribute.',
            '*.unique' => ':Attribute này đã tồn tại.',
            '*.numeric' => ':Attribute phải là số.',
            '*.nullable' => ':Attribute là tùy chọn.',
            '*.max' => ':Attribute tối đa :max ký tự',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $response = validationErrors($validator->errors());
        throw (new ValidationException($validator, $response));
    }
}
