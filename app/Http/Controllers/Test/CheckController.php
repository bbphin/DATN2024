<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @tags Test
 */
class CheckController extends Controller
{
    /**
     * Nhân viên
     */
    public function staff()
    {
        return response()->json([
            'success' => true,
            'message' => 'Staff',
        ], 200);
    }
    /**
     * Admin
     */
    public function admin()
    {
        return response()->json([
            'success' => true,
            'message' => 'Admin',
        ], 200);
    }
    /**
     * Thông tin người dùng
     */
    public function info(Request $request)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            return response()->json([
                'success' => true,
                'user' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập tài khoản',
            ], 401);
        }
    }
}
