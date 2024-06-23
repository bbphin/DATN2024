<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Client\Order\OrderController;


Route::controller(OrderController::class)->name('order.')->group(function() {
    Route::post('order','checkout')->name('checkout');
});
