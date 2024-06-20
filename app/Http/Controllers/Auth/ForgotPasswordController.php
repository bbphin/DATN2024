<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * @tags Auth
 */
class ForgotPasswordController extends Controller
{
    /**
     * Quên mật khẩu.
     * @unauthenticated
     */
    public function sendResetLinkEmail(Request $request): \Illuminate\Http\JsonResponse
    {

        $request->validate([
            /**
             * @example nguyenthanhsont123@gmail.com
             */
            'email' => 'required|email'
        ]);

        $credentials = $request->only('email');

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email không tồn tại.'], 400);
        }
        $password_reset_tokens =  DB::table('password_reset_tokens')->where('email', $request->email);
        if ($password_reset_tokens->exists()) {
            $password_reset_tokens->delete();
        }
        $key = Config::get('app.key');
        $token = Str::random(16);
        $encryptedToken = encrypt($token, $key);
        $password_reset = DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' =>  $encryptedToken,
            'created_at' => Carbon::now()
        ]);
        if ($password_reset) {
            $resetLink = url(config('app.url') . route('password.reset', ['token' => $encryptedToken, 'email' => $user->email], false));
            $mail = Mail::to($user->email)->send(new ResetPassword($resetLink));
            $message = "Đã gửi thành công. Vui lòng kiểm tra inbox hoặc spam.";
            $status = true;
        } else {
            $message = 'Lỗi trong quá trình thực thi.';
            $status = false;
        }

        $data = [
            'success' => $status,
            'message' => $message,
            'result' => [
                'email' => $user->email,
                'token' =>  $encryptedToken,
            ]
        ];

        return response()->json($data, 200);
    }
}
