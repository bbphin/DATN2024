<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Client\Cart\CartController;

Route::apiResource('cart', CartController::class);
