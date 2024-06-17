<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Client\Review\ReviewController;

Route::apiResource('review', ReviewController::class);
