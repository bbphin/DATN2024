<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Account\AccountController;

Route::apiResource('account', AccountController::class);
