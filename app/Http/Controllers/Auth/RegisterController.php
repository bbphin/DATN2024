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
            return response()->json(['errors' => $validator->errors()], 422);
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

        $data = [
            'success' => true,
            'result' => new UserResource($user),
            'extra' => [
                'authToken' => $token->plainTextToken,
                'tokenType' => 'Bearer',
            ],
        ];

        return response()->json($data, 201);
    }
}
