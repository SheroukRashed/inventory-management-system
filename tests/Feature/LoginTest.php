<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * @return void
     */
    public function test_required_email_for_login(): void
    {
        $loginData = ['password' => 'pass123'];

        $this->json('POST', 'api/login', $loginData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The email field is required.',
                'data' => null
            ]);
    }

    /**
     * @return void
     */
    public function test_valid_format_email_for_login(): void
    {
        $loginData = ['email' => 'testlogin*user.com', 'password' => 'pass123'];

        $this->json('POST', 'api/login', $loginData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The email field must be a valid email address.',
                'data' => null
            ]);
    }

    /**
     * @return void
     */
    public function test_required_password_for_login(): void
    {
        $loginData = ['email' => 'testlogin@user.com'];

        $this->json('POST', 'api/login', $loginData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The password field is required.',
                'data' => null
            ]);
    }
    
    /**
     * @return [type]
     */
    public function test_user_login_fails_on_wrong_credentials()
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $loginData = ['email' => 'testlogin@user.com', 'password' => 'wrongPass123'];

        $this->json('POST', 'api/login', $loginData)
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized.',
                'data' => null
            ]);

    }

    /**
     * @return [type]
     */
    public function test_user_login_successfully()
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $loginData = ['email' => 'testlogin@user.com', 'password' => 'pass123'];

        $this->json('POST', 'api/login', $loginData)
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data'=> [
                    'token',
                    'name'
                ]
            ]);

    }
}
