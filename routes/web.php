<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\TokenVerificationMiddleware;

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

// Route::get('/', function () {
//     return view('welcome');
// });





// api=============

Route::post('user-registeration',[UserController::class, 'UserRegistration']);
Route::post('user-login',[UserController::class, 'UserLogin']);
Route::post('send-otp',[UserController::class, 'SendOTPCode']);
Route::post('verify-otp',[UserController::class, 'VerifyOTP']);
Route::post('reset-password',[UserController::class, 'ResetPassword'])->middleware([TokenVerificationMiddleware::class]);


// ============user-logout=============
Route::get('logout',[UserController::class, 'UserLogout'])->middleware([TokenVerificationMiddleware::class]);
// page route
Route::get('/',[HomeController::class,'HomePage']);
Route::get('/userLogin',[UserController::class,'LoginPage']);
Route::get('/userRegistration',[UserController::class,'RegistrationPage']);
Route::get('/sendOtp',[UserController::class,'SendOtpPage']);
Route::get('/verifyOtp',[UserController::class,'VerifyOTPPage']);
Route::get('/resetPassword',[UserController::class,'ResetPasswordPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/dashboard',[DashboardController::class,'DashboardPage'])->middleware([TokenVerificationMiddleware::class]);

// =========================
