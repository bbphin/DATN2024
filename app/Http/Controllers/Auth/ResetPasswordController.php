<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * @tags Auth
 */
class ResetPasswordController extends Controller
{
    /**
     * Thay đổi mật khẩu.
     *
     * `Sau khi có link gửi về mail.`
     * @unauthenticated
     */
    public function reset(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            /**
             * @example nguyenthanhsont123@gmail.com
             */
            'email' => 'required|email',
            /**
             * @example thanhson
             */
            'password' => 'required|string|confirmed',
            /**
             * @example 72984744nakbhjdvfybdls
             */
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');
        $check = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if(!$check){
            return response()->json(['success' => false, 'message' => 'Thông tin không chính xác.'], 400);
        }
        $token = $check->token;
        if ($check && $token) {
            if ($check->token === $token) {
                $createdAt = Carbon::parse($check->created_at);
                $expiresAt = $createdAt->addMinutes(60);
                $currentTime = now();
                if ($currentTime->lte($expiresAt)) {
                    $user = User::where('email', $request->email)->first();
                    if ($user) {
                        $user->password = Hash::make($request->password);
                        $user->save();
                        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                        if (Hash::check($request->input('password'), $user->password)) {
                            return $this->createUserApiToken($user, trans('change pass'));
                        }
                    }
                    return response()->json(['success' => false, 'message' => 'Thông tin không chính xác.'], 400);
                }else{
                    return response()->json(['success' => false, 'message' => 'Token đã hết hạn hoặc không chính xác.'], 400);
                }
            }
        }
    }
    protected function createUserApiToken($user, $deviceName = null, $message = null): \Illuminate\Http\JsonResponse
    {
        $user->tokens()->delete();
        $deviceName = $deviceName ?? $user->email;
        $token = $user->createToken($deviceName);

        $data = [
            'success' => true,
            'message' => $message,
            'result'  => new UserResource($user),
            'extra'   => [
                'authToken' => $token->plainTextToken,
                'tokenType' => 'Bearer',
            ],
        ];

        return response()->json($data);
    }
}
