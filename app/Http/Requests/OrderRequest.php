<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Hoặc kiểm tra quyền truy cập của người dùng
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'order_number' => 'required|string|unique:orders',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'payment_method' => 'required|in:credit_card,paypal,cash_on_delivery',
        ];
    }
}
