<?php

namespace Tests\Feature;

use App\Http\Resources\OrderItemResourceCollection;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\IngredientSeeder;
use Database\Seeders\InventorySeeder;
use Tests\TestCase;

class OrderItemTest extends TestCase
{
    const COMPOUND_PRODUCT_SIZE = 3;

    /**
     * @return void
     */
    public function test_order_item_creation_for_both_types_of_products(): void
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

        $createdOrder = $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
            'Accept' => 'application/json'
        ])->json('POST', 'api/orders', $orderData)->assertStatus(200);
        $createdOrderData = $createdOrder->json()['data'];
        $createdOrderId = $createdOrderData['id'];
        $createdOrderItemsCount = $createdOrderData['order_items']['meta']['order_items_count'];

        $orderItems = Order::findOrFail($createdOrderId)->items;
        $orderItemsExpectedArray = (new OrderItemResourceCollection($orderItems))->resolve();
        $orderItemsExpectedArray['items'] = json_decode(($orderItemsExpectedArray['items'])->toJson(),true);
        $this->assertCount($createdOrderItemsCount, $orderItems);
        $this->assertContains($orderItemsExpectedArray, $createdOrderData);
        $this->assertEquals($orderItemsExpectedArray, $createdOrderData['order_items']);
    }
}
