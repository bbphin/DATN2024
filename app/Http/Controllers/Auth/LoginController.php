<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;

/**
 * @tags Auth
 */
class LoginController extends Controller
{
    /**
     * Đăng nhập.
     * @unauthenticated
     */

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [

                /**
                 * @example nguyenthanhsont123@gmail.com
                 */
                'email' => 'required|string|email',
                /**
                 * @example thanhson
                 */
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return validationErrors($validator->errors());
            }

            if (!auth()->attempt($request->only('email', 'password'))) { // check người dùng | ! khác
                return errors('Tài khoản hoặc mật khẩu không đúng.');
            }

            $user = auth()->guard('api')->user(); // lấy data
            $user->tokens()->delete(); // xoá token
            $token = $user->createToken($request->email, $user->withAccessTokenAbilities()); // tạo mới token

            $extra = [
                'extra' => [
                    'authToken' => $token->plainTextToken,
                    'tokenType' => 'Bearer',
                    'role' => $user->role,
                ],
            ];
            return success('Đăng nhập thành công', new UserResource($user, $extra));
        } catch (\Exception $e) {
            return errors($e->getMessage());
        }
    }
    /**
     * Đăng xuất.
     *
     * Cần sử dụng header Authorization Bearer `Token`.
     */
    public function logout(Request $request)
    {
        try {
            if (Auth::guard('api')->check()) { // check login
                Auth::guard('api')->user()->tokens()->delete();
                return success('Đăng xuất thành công');
            } else {
                return errors('Không tìm thấy người dùng đăng nhập.');
            }
        } catch (\Exception $e) {
            return errors($e->getMessage());
        }
    }
}
