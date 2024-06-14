<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $token = $request->bearerToken();
        if(Auth::guard('api')->check()){
            if (Auth::guard('api')->user()->role >= 1 ) {
                return $next($request);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Không có quyền truy cập',
                ], 401);
            }
        }else{
            if ($token) {
                $message = 'Token đã hết hạn. Vui lòng đăng nhập lại';
            } else {
                $message = 'Vui lòng đăng nhập tài khoản';
            }
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 401);
        }
    }
}
