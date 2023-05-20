<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    /**
     * @return void
     */
    public function test_required_name_for_registration(): void
    {
        $registrationData = [
            'email' => 'testregister@user.com',
            'password' => 'pass123',
            'c_password' => 'pass123'
        ];

        $this->json('POST', 'api/register', $registrationData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The name field is required.',
                'data' => null
            ]);
    }

    /**
     * @return void
     */
    public function test_required_email_for_registration(): void
    {
        $registrationData = [
            'name' => 'testuser',
            'password' => 'pass123',
            'c_password' => 'pass123'
        ];

        $this->json('POST', 'api/register', $registrationData)
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
    public function test_valid_format_email_for_registration(): void
    {
        $registrationData = [
            'name' => 'testuser',
            'email' => 'testregister*user.com',
            'password' => 'pass123',
            'c_password' => 'pass123'
        ];
        
        $this->json('POST', 'api/register', $registrationData)
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
    public function test_unique_email_for_registration(): void
    {
        User::create([
            'name' => 'testuser2',
            'email' => 'testregister@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $registrationData = [
            'name' => 'testuser',
            'email' => 'testregister@user.com',
            'password' => 'pass123',
            'c_password' => 'pass123'
        ];
        
        $this->json('POST', 'api/register', $registrationData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The email has already been taken.',
                'data' => null
            ]);
    }

    /**
     * @return void
     */
    public function test_required_password_for_registration(): void
    {
        $registrationData = [
            'name' => 'testuser',
            'email' => 'testregister@user.com',
            'c_password' => 'pass123'
        ];

        $this->json('POST', 'api/register', $registrationData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The password field is required. (and 1 more error)',
                'data' => null
            ]);
    }

    /**
     * @return void
     */
    public function test_required_confirm_password_for_registration(): void
    {
        $registrationData = [
            'name' => 'testuser',
            'email' => 'testregister@user.com',
            'password' => 'pass123'
        ];

        $this->json('POST', 'api/register', $registrationData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The c password field is required.',
                'data' => null
            ]);
    }

    /**
     * @return void
     */
    public function test_wrong_confirm_password_for_registration(): void
    {
        $registrationData = [
            'name' => 'testuser',
            'email' => 'testregister@user.com',
            'password' => 'pass123',
            'c_password' => 'wrongPass123'
        ];

        $this->json('POST', 'api/register', $registrationData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The c password field must match password.',
                'data' => null
            ]);
    }
    /**
     * @return void
     */
    public function test_user_registration_successfully(): void
    {
        $registrationData = [
            'name' => 'testuser',
            'email' => 'testregister@user.com',
            'password' => 'pass123',
            'c_password' => 'pass123'
        ];

        $this->json('POST', 'api/register', $registrationData)
            ->assertStatus(201)
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
