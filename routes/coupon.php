<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Coupon\CouponController;

Route::apiResource('coupons', CouponController::class);
