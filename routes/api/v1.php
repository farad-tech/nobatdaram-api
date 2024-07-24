<?php

use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\User\AppointmentController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\RecentViewController;
use App\Http\Controllers\User\VerificationCodeController;
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


Route::prefix('/auth')->group(function () {
  Route::post('/login-register', [AuthenticationController::class, 'loginRegister']);

  Route::post('/get-reset-password-code', [ResetPasswordController::class, 'sendCode']);

  Route::post('/get-reset-password-permission', [ResetPasswordController::class, 'getPermissionByVerification']);
  Route::post('/check-reset-password-token', [ResetPasswordController::class, 'checkPermissionByVerification']);
  Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword']);
});


Route::middleware('auth:sanctum')->group(function () {
  Route::get('/check-auth', [AuthenticationController::class, 'checkAuth']);
  Route::post('/check-code', [VerificationCodeController::class, 'checkCode']);
  Route::post('/send-code', [VerificationCodeController::class, 'sendCodeToUser'])->middleware(['throttle:1,2']); // one request per 2 minute (throttle:1,2)

  Route::delete('/profile/delete-avatar', [ProfileController::class, 'deleteAvatar']);
  Route::get('/profile/search', [ProfileController::class, 'search']);
  Route::apiResource('/profile/recent', RecentViewController::class)->only('index');
  Route::apiResource('/profile', ProfileController::class)->except('update','destroy');

  Route::apiResource('appointment', AppointmentController::class);
});

// Route::apiResource('appointment', AppointmentController::class);
