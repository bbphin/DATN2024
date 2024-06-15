<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Cho phép tất cả các yêu cầu
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'content.required' => 'Nội dung bài viết không được để trống.',
            'content.string' => 'Nội dung bài viết phải là chuỗi.',
            'image.required' => 'Ảnh đại diện không được để trống.',
            'image.image' => 'Ảnh đại diện không đúng định dạng.',
            'image.mimes' => 'Ảnh đại diện phải là định dạng: jpeg, png, jpg, gif, svg.',
            'image.max' => 'Ảnh đại diện không được vượt quá 2048 KB.',
        ];
    }
}
