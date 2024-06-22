<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Category\CategoryController;

Route::apiResource('categories', CategoryController::class);
Route::post('categories/search',[CategoryController::class,'search'])->name('categories.search');
