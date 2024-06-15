<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Blog\BlogController;

Route::apiResource('blog', BlogController::class);
