<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Color\ColorController;


Route::apiResource('colors',ColorController::class);
