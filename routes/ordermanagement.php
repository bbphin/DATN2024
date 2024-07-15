<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\Ordermanagement\OrdermanagementController;

Route::apiResource('ordermanagement', OrdermanagementController::class);