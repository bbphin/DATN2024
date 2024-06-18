<?php

namespace App\Http\Controllers\Api\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = User::select('id', 'name', 'email', 'role', 'balance', 'is_banned','created_at')->get();
        $response = [
            'success' => true,
            'message' => 'In danh sách người dùng thành công',
            'data' => $data,
            'extra' => [
                'authToken' => request()->bearerToken(),
                'tokenType' => 'Bearer',
                'role' => auth()->guard('api')->user()->role,
            ],
        ];

        return response()->json($response, 200);
    }
}
