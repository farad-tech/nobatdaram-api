<?php

use App\Http\Controllers\Auth\AuthenticationController;
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
});


Route::middleware('auth:sanctum')->group(function () {
  Route::get('/check-auth', [AuthenticationController::class, 'checkAuth']);
  Route::post('/check-code', [AuthenticationController::class, 'checkCode']);
});