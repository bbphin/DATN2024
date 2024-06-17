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





Route::prefix('auth')->group(function () {
    Route::post('signup', [RegisterController::class, 'register'])->name('signup');

    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    // Route::post('password/token', [ResetPasswordController::class, 'sendResetToken'])->name('password.token');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

    Route::controller(SocialController::class)->group(function ($router) {
        $router->pattern('provider', 'google');
        Route::get('{provider}', 'getProviderTargetUrl');
        Route::get('{provider}/callback', 'handleProviderCallback');
    });
});

Route::middleware('checkauth')->group(function () {
    Route::get('user',[CheckController::class,'info'])->name('testgetinfo');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('user',[CheckController::class,'info'])->name('testgetinfo');

    Route::post('subscriber',[ClientSubscriberController::class,'add'])->name('addSubscriber');

    Route::middleware('checkadmin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('test/admin',[CheckController::class,'admin'])->name('testadmin');
        Route::prefix('account')->group(
            function () {
                Route::get('/', [AccountController::class, 'index'])->name('account');
                Route::post('add', [AccountController::class, 'add'])->name('postAddAccount');
                Route::put('edit/{id}', [AccountController::class, 'edit'])->name('postEditAccount');
                Route::get('delete/{id}', [AccountController::class, 'delete'])->name('deleteAccount')->where(['id' => '[0-9]+']);
                Route::get('list/trash', [AccountController::class, 'getTrash'])->name('listTrashAccount')->where(['id' => '[0-9]+']);

            }
        );
        Route::prefix('subscriber')->group(
            function () {
                Route::get('/', [SubscriberController::class, 'index'])->name('subscriber');
                Route::post('/send', [SubscriberController::class, 'send'])->name('sendSubscriber');
                Route::post('/save', [SubscriberController::class, 'save'])->name('saveSubscriber');
            }
        );
    });
    Route::middleware('checkstaff')->group(function () {
        Route::get('test/staff',[CheckController::class,'staff'])->name('teststaff');
    });
});
