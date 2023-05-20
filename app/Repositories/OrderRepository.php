<?php

namespace App\Repositories;

use App\Interfaces\OrderRepositoryInterface;
use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{   
    /**
     * @param array $orderDetails
     * 
     * @return Order
     */
    public function createOrder(array $orderDetails)
    {
        return Order::create($orderDetails);
    }
}