<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class AccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('POST')) {
            return [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'phone' => 'nullable|string',
                'address' => 'nullable|string',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                // 'balance' => 'numeric|min:0',
                'is_banned' => 'boolean',
                'role' => 'boolean',
            ];
        }
        
        if ($this->isMethod('PUT')) {
            $id = $this->route('account');
            return [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:6',
                'phone' => 'nullable|string',
                'address' => 'nullable|string',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                // 'balance' => 'numeric|min:0',
                'is_banned' => 'boolean',
                'role' => 'boolean',
            ];
        }

        return [];
    }

    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự',
            'image' => 'Ảnh đại diện phải là định dạng jpeg, png, jpg, gif, svg và không quá 2MB',
            // 'numeric' => ':attribute phải là số',
            'boolean' => ':attribute phải là true hoặc false',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Tên người dùng',
            'email' => 'Email',
            'password' => 'Mật khẩu',
            'phone' => 'Số điện thoại',
            'address' => 'Địa chỉ',
            'avatar' => 'Ảnh đại diện',
            // 'balance' => 'Số dư trong tài khoản',
            'is_banned' => 'Trạng thái tài khoản',
            'role' => 'Quyền hạn',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = [
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
