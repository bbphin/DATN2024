<?php

use App\Models\ProductCategory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\ProductCategory\ProductCategoryController;

Route::apiResource('product-category', ProductCategoryController::class);
