<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Resources\UserResource;

/**
 * @tags Auth
 */
class RegisterController extends Controller
{
    /**
     * Đăng ký.
     * @unauthenticated
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                /**
                 * @example Nguyễn Thành Sơn

                 */

                'name' => 'required|string|max:100',
                /**
                 * @example nguyenthanhsont123@gmail.com
                 */
                'email' => 'required|string|email|max:255|unique:users',

                'password' => 'required|string|min:5',
            ]);

            if ($validator->fails()) {
                return validationErrors($validator->errors());
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 0,
                'password' => Hash::make($request->password),
            ]);
            // token check login FE
            $user->tokens()->delete();
            /** @ignoreParam */
            $deviceName = $request->input('device_name', $request->email);
            $token = $user->createToken($deviceName);

            $extra = [
                'extra' => [
                    'authToken' => $token->plainTextToken,
                    'tokenType' => 'Bearer',
                    'role' => $user->role,
                ],
            ];
            return success('Đăng ký thành công', new UserResource($user, $extra));
        } catch (\Exception $e) {
            return errors($e->getMessage());
        }
    }
}
