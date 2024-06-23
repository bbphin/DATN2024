<?php

use App\Http\Controllers\Api\Admin\Account\AccountController;
use App\Http\Controllers\Api\Client\Account\ClientAccountController;
use App\Http\Controllers\Test\CheckController;
use Illuminate\Support\Facades\Route;

Route::middleware('checkauth')->prefix('account')->name('account.')->group(function () {
    Route::get('get-info', [ClientAccountController::class, 'index'])->name('getInfo');
    Route::get('statistical', [ClientAccountController::class, 'statistical'])->name('getStatistical');
    Route::get('invoice', [ClientAccountController::class, 'invoice'])->name('getInvoice');
    Route::post('change-password', [ClientAccountController::class, 'changePassword'])->name('postChangePassword');
});
Route::middleware('checkauth')->group(function () {
    Route::middleware('checkadmin')->prefix('admin')->name('admin.')->group(function () {
        Route::prefix('account')->group(
            function () {
                Route::get('test/admin', [CheckController::class, 'admin'])->name('testadmin');

                Route::get('/', [AccountController::class, 'index'])->name('account');
                Route::post('add', [AccountController::class, 'add'])->name('postAddAccount');
                Route::put('edit/{id}', [AccountController::class, 'edit'])->name('postEditAccount');
                Route::get('delete/{id}', [AccountController::class, 'delete'])->name('deleteAccount')->where(['id' => '[0-9]+']);
                Route::get('list/trash', [AccountController::class, 'getTrash'])->name('listTrashAccount')->where(['id' => '[0-9]+']);
            }
        );
    });
    Route::middleware('checkstaff')->group(function () {
        Route::get('test/staff', [CheckController::class, 'staff'])->name('teststaff');
    });
});
