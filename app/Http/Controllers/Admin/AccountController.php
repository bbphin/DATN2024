<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\AccountFormRequest;
use App\Http\Requests\Account\AddAccountFormRequest;
use App\Http\Requests\Account\EditAccountFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @tags Admin\Account
 */
class AccountController extends Controller
{
    /**
     * Danh sách người dùng
     */
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

    /**
     * Thêm người dùng
     */
    public function add(AddAccountFormRequest $request)
    {
        $user = new User;
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user->fill($data)->save();
        $token = $request->bearerToken();
        $response = [
            'success' => true,
            'message' => 'Thêm tài khoản thành công',
            'extra' => [
                'authToken' => $token,
                'tokenType' => 'Bearer',
                'role' => $user->role,
            ],
        ];
        return response()->json($response, 200);
    }


    /**
     * Sửa người dùng.
     * @param int $id ID người dùng
     */
    public function edit(EditAccountFormRequest $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy người dùng.'], 404);
        }
        $data = $request->validated();
        /**
         * @example thanhson
         */
        if ($request->has('reset_password') && $request->input('reset_password')) {
            $data['password'] = Hash::make($request->input('reset_password'));
        }
        $user->update($data);
        $token = $request->bearerToken();
        $response = [
            'success' => true,
            'message' => 'Sửa tài khoản thành công',
            'extra' => [
                'authToken' => $token,
                'tokenType' => 'Bearer',
                'role' => $user->role,
            ],
        ];
        return response()->json($response, 200);
    }



    /**
     * Xoá người dùng.
     * @param int $id ID người dùng
     */
    public function delete($id)
    {
        $user = User::withTrashed()->find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy người dùng.'], 404);
        }
        // Kiểm tra nếu người dùng đã bị xóa (nằm trong thùng rác)
        if ($user->trashed()) {
            // Xóa vĩnh viễn người dùng khỏi cơ sở dữ liệu
            $user->forceDelete();
            return response()->json(['success' => true, 'message' => 'Xóa người dùng thành công.'], 200);
        }
        // Nếu người dùng chưa bị xóa, đưa vào thùng rác trước
        $user->delete();
        return response()->json(['success' => true, 'message' => 'Đưa người dùng vào thùng rác thành công.'], 200);
    }


    /**
     * Danh sách người dùng ngủ trong thùng rác.
     */
    public function getTrash()
    {
        $data = User::onlyTrashed()->get();
        $response = [
            'success' => true,
            'message' => 'In danh sách thành công',
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
