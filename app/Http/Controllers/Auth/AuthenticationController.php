<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserController;
use App\Models\User;
use App\Rules\PhoneOrEmail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{
    public function loginRegister(Request $request)
    {
        $request->validate([
            'phoneoremail' => ['required', new PhoneOrEmail],
            'password' => 'required|min:6',
        ]);

        $user = User::where('phoneoremail', $request->phoneoremail)->first();

        if ($user == null) {

            $user = UserController::createUser($request->phoneoremail, $request->password);

            return self::createLoginToken($user);

        } else {

            if (Hash::check($request->password, $user->password)) {

                return self::createLoginToken($user);
            }

            return response('failed', 401);
        }
    }

    public static function createLoginToken(Object $user)
    {
        $login = $user->createToken('user');
        Log::info($login);
        return $login->plainTextToken;
    }

    public static function checkAuth()
    {
        $response = response('authenticated');

        $user = User::find(auth()->id());

        if($user->account_verified_at == null) {
            $response = response('authenticated', 202);

            // send code method should be placed here
        }

        return $response;
    }
}
