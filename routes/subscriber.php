<?php

use App\Http\Controllers\Api\Admin\Subscriber\SubscriberController;
use App\Http\Controllers\Api\Client\Subscriber\ClientSubscriberController;
use Illuminate\Support\Facades\Route;

Route::middleware('checkauth')->group(function () {
    Route::post('subscriber',[ClientSubscriberController::class,'add'])->name('addSubscriber');
});
Route::middleware('checkauth')->group(function () {
    Route::middleware('checkadmin')->prefix('admin')->name('admin.')->group(function () {
        Route::prefix('subscriber')->group(
            function () {
                Route::get('/', [SubscriberController::class, 'index'])->name('subscriber');
                Route::post('/send', [SubscriberController::class, 'send'])->name('sendSubscriber');
                Route::post('/save', [SubscriberController::class, 'save'])->name('saveSubscriber');
            }
        );
    });
});
