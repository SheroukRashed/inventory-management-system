<?php

namespace App\Repositories;

use App\Interfaces\InventoryRepositoryInterface;
use App\Models\Inventory;

class InventoryRepository implements InventoryRepositoryInterface
{
    /**
     * @param mixed $inventoryId
     * @param array $inventoryDetails
     * 
     * @return void
     */
    public function updateInventory($inventoryId, array $inventoryDetails)
    {
        $isLimited = $inventoryDetails['is_limited'];
        if (!(bool)$isLimited) {
            $availableQty = $inventoryDetails['left_in_stock_quantity'];
            $thresholdQty = $inventoryDetails['threshold_quantity'];
            if ($availableQty <= $thresholdQty) {
                $inventoryDetails['is_limited'] = true;
            }
        }
        Inventory::findOrFail($inventoryId)->update($inventoryDetails);
    }
}