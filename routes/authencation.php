<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('signup', [RegisterController::class, 'register'])->name('signup');

    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    // Route::post('password/token', [ResetPasswordController::class, 'sendResetToken'])->name('password.token');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

    Route::controller(SocialController::class)->group(function ($router) {
        $router->pattern('provider', 'google');
        Route::get('{provider}', 'getProviderTargetUrl');
        Route::get('{provider}/callback', 'handleProviderCallback');
    });
});
Route::middleware('checkauth')->group(function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
});
