<?php

namespace App\Interfaces;

use App\Models\Order;
interface OrderRepositoryInterface
{
    /**
     * @param array $orderDetails
     * 
     * @return Order
     */
    public function createOrder(array $orderDetails);
}