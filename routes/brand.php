<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Brand\BrandController;

Route::apiResource('brands', BrandController::class);
