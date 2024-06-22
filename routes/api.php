<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\ClientSubscriberController;
use App\Http\Controllers\Test\CheckController;
use Illuminate\Support\Facades\Auth;
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
// Wishlist
require_once __DIR__ . '/wishlist.php';
// Review
require_once __DIR__ . '/review.php';
// Cart
require_once __DIR__ . '/cart.php';
// Auth
require_once __DIR__ . '/authencation.php';
// Subscriber
require_once __DIR__ . '/subscriber.php';
// Account
require_once __DIR__ . '/account.php';
// Dashboard - Home Admin
require_once __DIR__ . '/dashboard.php';
<<<<<<< HEAD

=======
>>>>>>> 1788e7318097979939cdc50bdb650bdbc87d9797
