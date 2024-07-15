<?php

namespace App\Http\Controllers\Api\Client\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Hiển thị thông tin của một người dùng.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json([
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Cập nhật thông tin của người dùng.
     *
     * 

     */
    public function update(UserRequest $request, User $user)
    {
        // Xử lý avatar nếu có
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time().'.'.$avatar->getClientOriginalExtension();
            $avatar->storeAs('avatars', $avatarName); // Lưu ảnh vào thư mục avatars trong thư mục lưu trữ
            $user->avatar = $avatarName; // Lưu tên file vào cơ sở dữ liệu
        }

        // Cập nhật thông tin người dùng từ dữ liệu yêu cầu
        $user->update($request->all());
        
        return response()->json([
            'message' => 'Cập nhật thông tin người dùng thành công',
            'data' => new UserResource($user)
        ]);
    }
}
