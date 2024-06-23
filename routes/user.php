<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Client\User\UserController;



Route::apiResource('user',UserController::class);
