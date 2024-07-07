<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerificationCodeController extends Controller
{

    public static function checkCode(Request $request)
    {
        $request->validate([
            'code' => 'digits:5'
        ]);

        $user = User::find(auth()->id());
        $user_code = $user->verification_code;

        if($request->code == $user_code) {

            $user->account_verified_at = Carbon::now();
            $user->verification_code = null;
            $user->save();

            return response('valid');
            
        } else {

            return response('unvalid',  409);

        }
    }

    public static function sendCodeToUser()
    {
        $user = User::find(auth()->id());

        $verification_code = rand(10000, 99999);

        $sendTo = $user->phoneoremail;

        

        $user->verification_code = $verification_code;
        $user->save();
    }
}
