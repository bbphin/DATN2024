<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Client\Order\OrderController;


Route::controller(OrderController::class)->name('order.')->group(function() {
    Route::get('orders','index')->name('index');
    Route::post('order','checkout')->name('checkout');
});
