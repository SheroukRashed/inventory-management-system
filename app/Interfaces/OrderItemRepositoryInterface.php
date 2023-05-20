<?php

namespace App\Interfaces;

use App\Models\OrderItem;
interface OrderItemRepositoryInterface
{
    /**
     * @param array $orderItemDetails
     * 
     * @return OrderItem
     */
    public function createItem(array $orderItemDetails);
}