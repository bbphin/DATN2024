<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Order\OrderController;

Route::apiResource('order', OrderController::class);
