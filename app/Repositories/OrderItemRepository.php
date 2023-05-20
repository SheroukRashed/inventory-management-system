<?php

namespace App\Repositories;

use App\Interfaces\OrderItemRepositoryInterface;
use App\Models\OrderItem;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    /**
     * @param array $orderItemDetails
     * 
     * @return OrderItem
     */
    public function createItem(array $orderItemDetails)
    {
        return OrderItem::create($orderItemDetails);
    }
}