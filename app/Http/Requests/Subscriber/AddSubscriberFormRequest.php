<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Foundation\Http\FormRequest;

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


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->count() > 0) {
                throw new \Illuminate\Validation\ValidationException(
                    $validator,
                    response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422)
                );
            }
        });
    }
}
