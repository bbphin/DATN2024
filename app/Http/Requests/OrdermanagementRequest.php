<?php 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrdermanagementRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'order_status' => 'required|string',
            'payment_method' => 'nullable|string',
            'note' => 'nullable|string',
        ];
    }
}
