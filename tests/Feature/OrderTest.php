<?php

namespace Tests\Feature;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\User;
use Tests\TestCase;

class OrderTest extends TestCase
{
   /**
     * @return void
     */
    public function test_failed_placing_order_for_unauthorized_users(): void
    {
        $orderData = [
            'products' => [
                ['product_id' => '4', 'quantity' => '3'],
                ['product_id' => '1', 'quantity' => '1'],
                ['product_id' => '2', 'quantity' => '1']       
            ] 
        ];

        $this->json('POST', 'api/orders', $orderData)
            ->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Forbidden. The user does not have the permissions to perform this action.',
                'data' => null
            ]);
    }

    /**
     * @return void
     */
    public function test_required_products_for_placing_order(): void
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $orderData = [
            ['product_id' => '4', 'quantity' => '3'],
            ['product_id' => '1', 'quantity' => '1'],
            ['product_id' => '2', 'quantity' => '1']  
        ];
        
        $loginData = ['email' => 'testlogin@user.com', 'password' => 'pass123'];
        $response = $this->json('POST', 'api/login', $loginData);
        $token = $response->json()['data']['token'];

        $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
            'Accept' => 'application/json'
        ])->json('POST', 'api/orders', $orderData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The products field is required.',
                'data' => NULL
            ]);
    }
    /**
     * @return void
     */
    public function test_required_product_id_for_placing_order(): void
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $orderData = [
            'products' => array(
                ['product_id' => '4', 'quantity' => '3'],
                ['quantity' => '1'],
                ['product_id' => '2', 'quantity' => '1']      
            ) 
        ];
        
        $loginData = ['email' => 'testlogin@user.com', 'password' => 'pass123'];
        $response = $this->json('POST', 'api/login', $loginData);
        $token = $response->json()['data']['token'];

        $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
            'Accept' => 'application/json'
        ])->json('POST', 'api/orders', $orderData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The products.1.product_id field is required.',
                'data' => NULL
            ]);
    }

     /**
     * @return void
     */
    public function test_exist_product_id_for_placing_order(): void
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $orderData = [
            'products' => array(
                ['product_id' => '5', 'quantity' => '3'],
                ['product_id' => '1', 'quantity' => '1'],
                ['product_id' => '2', 'quantity' => '1']
            ) 
        ];
        
        $loginData = ['email' => 'testlogin@user.com', 'password' => 'pass123'];
        $response = $this->json('POST', 'api/login', $loginData);
        $token = $response->json()['data']['token'];

        $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
            'Accept' => 'application/json'
        ])->json('POST', 'api/orders', $orderData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The selected products.0.product_id is invalid.',
                'data' => NULL
            ]);
    }

    /**
     * @return void
     */
    public function test_required_quantity_for_placing_order(): void
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $orderData = [
            'products' => array(
                ['product_id' => '4', 'quantity' => '3'],
                ['product_id' => '1'],
                ['product_id' => '2', 'quantity' => '1']
            ) 
        ];
        
        $loginData = ['email' => 'testlogin@user.com', 'password' => 'pass123'];
        $response = $this->json('POST', 'api/login', $loginData);
        $token = $response->json()['data']['token'];

        $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
            'Accept' => 'application/json'
        ])->json('POST', 'api/orders', $orderData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The products.1.quantity field is required.',
                'data' => NULL
            ]);
    }

    /**
     * @return void
     */
    public function test_numeric_quantity_for_placing_order(): void
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $orderData = [
            'products' => array(
                ['product_id' => '4', 'quantity' => '3'],
                ['product_id' => '1', 'quantity' => 'one'],
                ['product_id' => '2', 'quantity' => '1']      
            ) 
        ];
        
        $loginData = ['email' => 'testlogin@user.com', 'password' => 'pass123'];
        $response = $this->json('POST', 'api/login', $loginData);
        $token = $response->json()['data']['token'];

        $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
            'Accept' => 'application/json'
        ])->json('POST', 'api/orders', $orderData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The products.1.quantity field must be a number. (and 1 more error)',
                'data' => NULL
            ]);
    }

    /**
     * @return void
     */
    public function test_gth_zero_quantity_for_placing_order(): void
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $orderData = [
            'products' => array(
                ['product_id' => '4', 'quantity' => '3'],
                ['product_id' => '1', 'quantity' => '0'],
                ['product_id' => '2', 'quantity' => '1']      
            ) 
        ];
        
        $loginData = ['email' => 'testlogin@user.com', 'password' => 'pass123'];
        $response = $this->json('POST', 'api/login', $loginData);
        $token = $response->json()['data']['token'];

        $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
            'Accept' => 'application/json'
        ])->json('POST', 'api/orders', $orderData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'The products.1.quantity field must be greater than 0.',
                'data' => NULL
            ]);
    }

    /**
     * @return void
     */
    public function test_order_item_type_rule_for_placing_order(): void
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $orderData = [
            'products' => array(
                ['product_id' => '3', 'quantity' => '3'],
                ['product_id' => '1', 'quantity' => '1'],
                ['product_id' => '2', 'quantity' => '1']      
            ) 
        ];
        
        $loginData = ['email' => 'testlogin@user.com', 'password' => 'pass123'];
        $response = $this->json('POST', 'api/login', $loginData);
        $token = $response->json()['data']['token'];

        $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
            'Accept' => 'application/json'
        ])->json('POST', 'api/orders', $orderData)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Can\'t place the order .. '.
                'the first order item should be a compound product which is followed by it\'s ingredients',
                'data' => NULL
            ]);
    }

    /**
     * @return void
     */
    public function test_placing_order_successfully(): void
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $orderData = [
            'products' => array(
                ['product_id' => '4', 'quantity' => '3'],
                ['product_id' => '1', 'quantity' => '1'],
                ['product_id' => '2', 'quantity' => '1']      
            ) 
        ];
        
        $loginData = ['email' => 'testlogin@user.com', 'password' => 'pass123'];
        $response = $this->json('POST', 'api/login', $loginData);
        $token = $response->json()['data']['token'];

        $createdOrder = $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
            'Accept' => 'application/json'
        ])->json('POST', 'api/orders', $orderData)->assertStatus(200);
        $createdOrderData = $createdOrder->json()['data'];
        $createdOrderId = $createdOrderData['id'];

        $order = Order::findOrFail($createdOrderId);
        $orderExpectedArray = json_decode((new OrderResource($order))->toJson(),true);
        $this->assertEquals($orderExpectedArray, $createdOrderData);
    }
}
