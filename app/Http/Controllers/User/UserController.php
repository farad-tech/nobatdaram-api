<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public static function createUser($phoneoremail, $password)
    {

        return User::create([
            'name' => 'user-' . rand(10000, 99999),
            'phoneoremail' => $phoneoremail,
            'password' => Hash::make($password),
        ]);

    }

    public static function resetPassword($phoneoremail, $password) {

        return User::where('phoneoremail', $phoneoremail)->update([
            'password' => Hash::make($password),
        ]);

    }
}
