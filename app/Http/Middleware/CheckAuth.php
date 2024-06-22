<?php

namespace App\Http\Middleware;

use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAuth
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $token = $request->bearerToken(); //

        if (Auth::guard('sanctum')->check()) {
            return $next($request);
        } else {
            if ($token) {
                $message = 'Token đã hết hạn. Vui lòng đăng nhập lại';
            } else {
                $message = 'Vui lòng đăng nhập tài khoản';
            }
            return errors($message);
        }
    }
}
