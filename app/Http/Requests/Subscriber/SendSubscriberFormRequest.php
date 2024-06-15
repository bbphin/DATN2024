<?php

namespace App\Http\Requests\Subscriber;

use Illuminate\Foundation\Http\FormRequest;

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
