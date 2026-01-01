<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_request_password_reset_link(): void
    {
        $user=User::factory()->create([
            'email'=>'test@test.com'
        ]);

        $response = $this->postJson('/api/auth/forgot-password',[
            'email'=>$user->email
        ]);

        $response->assertOk()->assertJsonStructure(['message']);
    }

    public function test_if_email_does_not_exist()
    {
        $response=$this->postJson('/api/auth/forgot-password',[
            'email'=>'test@test.com'
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_reset_password()
    {
        $user=User::factory()->create([
            'email'=>'test@test.com',
            'password'=>Hash::make('12345678')
        ]);
        $token=Password::createToken($user);

        $response=$this->postJson('/api/auth/reset-password',[
            'email'=>$user->email,
            'token'=>$token,
            'password'=>'12347856',
            'password_confirmation'=>'12347856'
        ]);
        $response->assertOk()->assertJsonStructure(['message']);

        $user->refresh();
        $this->assertTrue(Hash::check('12347856', $user->password));
        $this->assertFalse(Hash::check('12345678', $user->password));
    }

    public function test_if_token_invalid()
    {
        $user=User::factory()->create();

        $response=$this->postJson('/api/auth/reset-password',[
            'email'=>$user->email,
            'token'=>'invalid',
            'password'=>'12347856',
            'password_confirmation'=>'12347856'
        ]);

        $response->assertUnprocessable()->assertJsonValidationErrors(['email']);
    }
}
