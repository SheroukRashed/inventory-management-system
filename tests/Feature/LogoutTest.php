<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /**
     * @return [type]
     */
    public function test_user_logout_failed()
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $this->json('POST', 'api/logout')
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
    public function test_user_logout_successfully()
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);
        
        $loginData = ['email' => 'testlogin@user.com', 'password' => 'pass123'];
        $response = $this->json('POST', 'api/login', $loginData);
        $token = $response->json()['data']['token'];

        $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
            'Accept' => 'application/json'
        ])->json('POST', 'api/logout')
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User logged out successfully.',
                'data' => []
            ]);
    }
}
