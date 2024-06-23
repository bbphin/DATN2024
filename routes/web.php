<?php

use App\Http\Controllers\PaymentController;

use App\Http\Controllers\TestPaymentController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('payments', TestPaymentController::class)->only(['index']);


Route::post('/payments/vnpay-payment', [PaymentController::class, 'vnpayPayment'])->name('payment.vnpay');
Route::post('/payments/zalo', [PaymentController::class, 'zaloPayment'])->name('payment.zalo');





