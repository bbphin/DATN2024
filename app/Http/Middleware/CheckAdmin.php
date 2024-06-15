<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
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
            if (Auth::guard('api')->user()->role == 10) {
                return $next($request);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Không có quyền truy cập',
                    'data' => Auth::guard('api')->user(),
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
