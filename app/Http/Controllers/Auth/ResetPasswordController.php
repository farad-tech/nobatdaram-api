<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\VerificationCodeController;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Rules\PhoneOrEmail;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\Feature\ForgetPasswordTokenTest;

class ResetPasswordController extends Controller
{
    
    public function sendCode(Request $request)
    {

        $request->validate([
            'phoneoremail' => ['required', new PhoneOrEmail]
        ]);

        $user = User::where('phoneoremail', $request->phoneoremail)->first();

        if($user == null) {

            return response('Not found', 404);

        } else {

            VerificationCodeController::sendCodeToUser($user);

        }

    }


    public function getPermissionByVerification(Request $request)
    {

        $request->validate([
            'code' => 'required|digits:5',
            'phoneoremail' => ['required', new PhoneOrEmail]
        ]);

        $user = User::where('phoneoremail', $request->phoneoremail)->first();
        $user_code = $user->verification_code;

        if ($request->code == $user_code) {


            $token = Str::random(40);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->phoneoremail],
                ['token' => $token ],
            );

            $user->verification_code = null;
            $user->save();

            return response($token);
        } else {

            return response('unvalid',  409);
        }
    }

    public function checkPermissionByVerification(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'phoneoremail' => ['required', new PhoneOrEmail]
        ]);

        $token = DB::table('password_reset_tokens')->where('email', $request->phoneoremail)->where('token', $request->token)->first();

        if($token !== null) {
            return response('It`s ok!');
        } else {
            return response('Permission denied!', 403);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'phoneoremail' => ['required', new PhoneOrEmail],
            'password' => 'required|confirmed|min:6',
        ]);

        $token = DB::table('password_reset_tokens')->where('email', $request->phoneoremail)->where('token', $request->token)->first();

        $canReset = false;
        if($token !== null) {
            $canReset = true;
        }

        if($canReset) {
            UserController::resetPassword($request->phoneoremail, $request->password);
            $response = response('password changed');
        } else {
            $response = response('access denied', 403);
        }

        return $response;
    }

}
