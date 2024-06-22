<?php

namespace App\Http\Requests\Subscriber;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
class SendSubscriberFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            /**
             * @var array
             * @example ["nguyenthanhsont123@gmail.com", "spthanhsondev@gmail.com"]
             */
            'to' => 'required',
            /**
             * @example Đây là tiêu đề của mail nè
             */
            'subject' => 'required|min:1|max:100',
             /**
             * @example Xin chào mấy bé nhó
             */
            'content' => 'required',
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
            '*.min' => ':Attribute tối thiểu :min ký tự',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $response = validationErrors($validator->errors());
        throw (new ValidationException($validator, $response));
    }
}
