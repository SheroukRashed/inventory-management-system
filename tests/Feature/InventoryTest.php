<?php

namespace Tests\Feature;

use App\Mail\LimitedStockEmail;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\IngredientSeeder;
use Database\Seeders\InventorySeeder;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    const COMPOUND_PRODUCT_SIZE = 3;
    /**
     * @return void
     */
    public function test_placing_order_fails_for_out_of_stock_items(): void
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $orderData = [
            'products' => array(
                ['product_id' => '4', 'quantity' => self::COMPOUND_PRODUCT_SIZE],
                ['product_id' => '1', 'quantity' => '1'],
                [
                    'product_id' => '3', 
                    'quantity' => (InventorySeeder::PRODUCT_3_MAX_QTY + 1) - (IngredientSeeder::PRODUCT_3_QTY * self::COMPOUND_PRODUCT_SIZE)
                ]      
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
                'message' => 'Can\'t place the order .. no available stock',
                'data' => NULL
            ]);
    }

    /**
     * @return void
     */
    public function test_sending_email_when_inventory_reaches_50_Percent(): void
    {
        Mail::fake();
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $orderData = [
            'products' => array(
                ['product_id' => '4', 'quantity' => self::COMPOUND_PRODUCT_SIZE],
                ['product_id' => '1', 'quantity' => '1'],
                [
                    'product_id' => '3', 
                    'quantity' => (InventorySeeder::PRODUCT_3_MAX_QTY / 2) - (IngredientSeeder::PRODUCT_3_QTY * self::COMPOUND_PRODUCT_SIZE)
                ]  
            ) 
        ];
        $loginData = ['email' => 'testlogin@user.com', 'password' => 'pass123'];
        $response = $this->json('POST', 'api/login', $loginData);
        $token = $response->json()['data']['token'];

        $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
            'Accept' => 'application/json'
        ])->json('POST', 'api/orders', $orderData)->assertStatus(200);

        Mail::assertSent(LimitedStockEmail::class);
    }

    /**
     * @return void
     */
    public function test_ingredient_products_stock_size_on_placing_order(): void
    {
        User::create([
            'name' => 'testuser',
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $orderData = [
            'products' => array(
                ['product_id' => '4', 'quantity' => self::COMPOUND_PRODUCT_SIZE],
                ['product_id' => '1', 'quantity' => '10'],
                ['product_id' => '3', 'quantity' => '10']      
            ) 
        ];

        $loginData = ['email' => 'testlogin@user.com', 'password' => 'pass123'];
        $response = $this->json('POST', 'api/login', $loginData);
        $token = $response->json()['data']['token'];

        $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
            'Accept' => 'application/json'
        ])->json('POST', 'api/orders', $orderData)->assertStatus(200);

        $productOneInventory = Product::findOrFail(1)->inventory;
        $productThreeInventory = Product::findOrFail(3)->inventory;

        $this->assertEquals(
            InventorySeeder::PRODUCT_1_MAX_QTY - (IngredientSeeder::PRODUCT_1_QTY * self::COMPOUND_PRODUCT_SIZE) - 10,
            $productOneInventory->left_in_stock_quantity,
            "actual value is not equals to expected"
        );

        $this->assertEquals(
            InventorySeeder::PRODUCT_3_MAX_QTY - (IngredientSeeder::PRODUCT_3_QTY * self::COMPOUND_PRODUCT_SIZE) - 10,
            $productThreeInventory->left_in_stock_quantity,
            "actual value is not equals to expected"
        );
    }
}
