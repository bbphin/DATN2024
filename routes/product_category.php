<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\ProductCategory\ProductCategoryController;
use App\Http\Controllers\Api\Client\ProductCategory\ProductCategoryController as ClientProductCategory;
Route::apiResource('product-category', ProductCategoryController::class);


// hien thi danh muc san pham theo danh muc cha
Route::controller(ClientProductCategory::class)->name('categories.')->group(function() {
    Route::get('categories/product-category/{id}','productCategory')->name('categories.product-category');
    Route::post('product-category/search','search')->name('product-category.search');
});

