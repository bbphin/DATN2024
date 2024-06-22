<?php

namespace App\Http\Requests\Account;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
class AddAccountFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('id');

        $rules = [
            /**
             * @example Nguyễn Thành Sơn
             */
            'name' => 'required|string|max:100',
            /**
             * @example nguyenthanhsont123@gmail.com
             */
            'email' => 'required|email|unique:users,email',
            /**
             * @example secret12345
             */
            'password' => 'required|string|min:5',
            /**
             * 0: người dùng, 1: nhân viên, 10: quản lý cấp cao
             * @example 10
             */
            'role' => 'required|numeric|in:0,1,10',
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
            '*.in' => ':Attribute phải là một trong các giá trị được chỉ định.',
            '*.gte' => ':Attribute phải lớn hơn hoặc bằng :value.',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $response = validationErrors($validator->errors());
        throw (new ValidationException($validator, $response));
    }
}
