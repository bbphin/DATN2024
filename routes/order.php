<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Client\Order\OrderController;


Route::controller(OrderController::class)->middleware('checkauth')->name('order.')->group(function() {
    Route::get('orders','index')->name('index');
    Route::post('order','checkout')->name('checkout');
    //VNPAY
    Route::get('vnpay/check', 'checkPayVNPAY')->name('checkPayVNPAY');
    //END VNPAY
});
