<?php

namespace App\Http\Controllers\Api\Admin\Account;

use App\Http\Controllers\Api\Admin\Account\Response;
use App\Http\Controllers\Api\Admin\Account\AccountResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\AccountFormRequest;
use App\Http\Requests\Account\AddAccountFormRequest;
use App\Http\Requests\Account\EditAccountFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @tags Admin\Account
 *
 */
class AccountController extends Controller
{
    /**
     * @authenticated
     * Danh sách người dùng
     */
    public function index()
    {
    //     try {
    //         $users = User::latest()->paginate(10); // Lấy danh sách người dùng, phân trang mỗi trang 10 người dùng
    //         return ApiResponse(true, Response::HTTP_OK, 'Successfully fetched users', AccountResource::collection($users));
    //     } catch (\Exception $e) {
    //         return ApiResponse(false, Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage(), null);
    //     }
     }

    public function add(AddAccountFormRequest $request)
    {
        try {
            $user = new User;
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $user->fill($data)->save();
            $token = $request->bearerToken();
            $response = [
                'extra' => [
                    'authToken' => $token,
                    'tokenType' => 'Bearer',
                    'role' => $user->role,
                ],
            ];
            return success('Thêm tài khoản thành công', $response);
        } catch (\Exception $e) {
            return errors($e->getMessage());
        }
    }


    /**
     * Sửa người dùng.
     * @param int $id ID người dùng
     */
    public function edit(EditAccountFormRequest $request, $id)
    {
        try {
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
                'extra' => [
                    'authToken' => $token,
                    'tokenType' => 'Bearer',
                    'role' => $user->role,
                ],
            ];
            return success('Sửa tài khoản thành công', $response);
        } catch (\Exception $e) {
            return errors($e->getMessage());
        }
    }



    /**
     * Xoá người dùng.
     * @param int $id ID người dùng
     */
    public function delete($id)
    {
        try {
            $user = User::withTrashed()->find($id);
            if (!$user) {
                return errors('Không tìm thấy người dùng');
            }
            // Kiểm tra nếu người dùng đã bị xóa (nằm trong thùng rác)
            if ($user->trashed()) {
                // Xóa vĩnh viễn người dùng khỏi cơ sở dữ liệu
                $user->forceDelete();
                return success('Xoá người dùng thành công');
            }
            // Nếu người dùng chưa bị xóa, đưa vào thùng rác trước
            $user->delete();
            return success('Xoá người dùng vào thúng rác thành công');
        } catch (\Exception $e) {
            return errors($e->getMessage());
        }
    }


    /**
     * Danh sách người dùng ngủ trong thùng rác.
     */
    public function getTrash()
    {
        try {
            $data = User::onlyTrashed()->get();
            $response = [
                'data' => $data,
                'extra' => [
                    'authToken' => request()->bearerToken(),
                    'tokenType' => 'Bearer',
                    'role' => auth()->guard('api')->user()->role,
                ],
            ];
            return success('In danh sách thành công', $response);
        } catch (\Exception $e) {
            return errors($e->getMessage());
        }
    }
}

