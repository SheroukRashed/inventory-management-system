<?php

namespace App\Interfaces;

interface InventoryRepositoryInterface
{
    /**
     * @param mixed $inventoryId
     * @param array $inventoryDetails
     * 
     * @return void
     */
    public function updateInventory($inventoryId, array $inventoryDetails);
}