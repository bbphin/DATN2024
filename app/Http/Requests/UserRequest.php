<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Bạn có thể thay đổi logic phân quyền ở đây nếu cần thiết
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $userId = $this->route('user')->id;

        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$userId,
            'phone' => 'sometimes|string|max:20',
            'address' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Định dạng và kích thước ảnh
            'is_banned' => 'sometimes|boolean',
            // Thêm các trường dữ liệu khác cần cập nhật
        ];
    }
}