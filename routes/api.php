<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// Size
require_once __DIR__ . '/size.php';
// Brand
require_once __DIR__ . '/brand.php';
// Color
require_once __DIR__ . '/color.php';
// Category
require_once __DIR__ . '/category.php';
// Product Category
require_once __DIR__ . '/product_category.php';
// Product
require_once __DIR__ . '/product.php';
// Coupon
require_once __DIR__ . '/coupon.php';
