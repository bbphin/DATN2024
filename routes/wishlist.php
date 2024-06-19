<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Client\WishList\WishListController;

Route::apiResource('wishlist', WishListController::class);
