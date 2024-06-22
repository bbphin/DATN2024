<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\OrderDetail\OrderDetailController;

Route::apiResource('order_detail', OrderDetailController::class);
