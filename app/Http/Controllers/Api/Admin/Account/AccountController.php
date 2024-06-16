<?php

namespace App\Http\Controllers\Api\Admin\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    /**
     * Hiển thị danh sách người dùng.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $users = User::latest()->paginate(10); // Lấy danh sách người dùng, phân trang mỗi trang 10 người dùng
            return ApiResponse(true, Response::HTTP_OK, 'Successfully fetched users', AccountResource::collection($users));
        } catch (\Exception $e) {
            return ApiResponse(false, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), null);
        }
    }

    /**
     * Lưu người dùng mới vào cơ sở dữ liệu.
     *
     *
     */
    

   
}

