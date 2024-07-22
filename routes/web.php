<?php

use App\Mail\SendVerifyCode;
use App\Models\User;
use App\SMS\VerifyCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

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

    // (new VerifyCode(['11111'], '09352760807'))->go();
    
    // Mail::to('farhadkarami@yahoo.com')->send(new SendVerifyCode('48377'));

    // return view('mail.verifyCode', ['code' => 48377]);

});
