<?php

use App\Http\Controllers\Api\Admin\Account\AccountController;
use App\Http\Controllers\Api\Admin\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('checkauth')->group(function () {
    Route::middleware('checkadmin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('home');
    });
    Route::middleware('checkstaff')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('home');
    });
});
