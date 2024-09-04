<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OtpController;
use Illuminate\Support\Facades\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('otp', [OtpController::class, 'sendOtp'])->name('otp.send');
Route::post('otp/verify', [OtpController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/resend-otp', [OtpController::class, 'resendOtp'])->name('otp.resend');

// Ensure this group does not override the root `/` route
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        // Change this route to something different if necessary
        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    }
);
