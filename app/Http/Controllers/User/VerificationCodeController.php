<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\SendVerifyCode;
use App\Models\User;
use App\SMS\VerifyCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class VerificationCodeController extends Controller
{

    public static function checkCode(Request $request)
    {
        $request->validate([
            'code' => 'digits:5'
        ]);

        $user = User::find(auth()->id());
        $user_code = $user->verification_code;

        if ($request->code == $user_code) {

            $user->account_verified_at = Carbon::now();
            $user->verification_code = null;
            $user->save();

            return response('valid');
        } else {

            return response('unvalid',  409);
        }
    }

    public static function sendCodeToUser($user = null)
    {
        $user = User::find(auth()->id()) ?? $user;

        $verification_code = rand(10000, 99999);

        $sendTo = strval($user->phoneoremail);

        $emailValidator = Validator::make(['email' => $sendTo], [
            'email' => 'required|email'
        ]);

        $phoneValidator = Validator::make(['phone' => $sendTo], [
            'phone' => 'required|numeric'
        ]);

        if($emailValidator->passes()) {

            Mail::to('farhadkarami@yahoo.com')->send(new SendVerifyCode($verification_code));

        } elseif($phoneValidator->passes()) {

            (new VerifyCode([$verification_code], $sendTo))->go();

        } else {
            Log::error("User with ID " . auth()->id() . " phone number or email is not a corrent phone number or email. (VerificationCodeController::sendCodeToUser())");
        }

        $user->verification_code = $verification_code;
        $user->save();
    }
}
