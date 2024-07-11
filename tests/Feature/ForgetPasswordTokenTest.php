<?php

namespace Tests\Feature;

use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForgetPasswordTokenTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $user = User::first();

        $token = '1232323';

        PasswordResetToken::create([
            'email' => $user->phoneoremail,
            'token' => $token,
        ]);

        assert(200);
    }
}
