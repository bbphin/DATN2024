<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Size\SizeController;



Route::apiResource('sizes',SizeController::class);
