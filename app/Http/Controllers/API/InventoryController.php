<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Repositories\InventoryRepository;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * @var InventoryRepository
     */
    protected InventoryRepository $inventoryRepository;

    /**
     * @param InventoryRepository $inventoryRepository
     */
    public function __construct(InventoryRepository $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

    /**
     * @param Request $request
     * @param Inventory $inventory
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Inventory $inventory)
    {
        $inventoryId = $inventory->id;

        $inventoryDetails = [
            'product_id' => $inventory->product_id,
            'stock_id' => $inventory->stock_id,
            'left_in_stock_quantity' => $inventory->left_in_stock_quantity - $request->quantity,
            'threshold_quantity' => $inventory->threshold_quantity,
            'is_limited'  => $inventory->is_limited,
        ];
        
        $this->inventoryRepository->updateInventory($inventoryId, $inventoryDetails);
        return $this->successResponse(Inventory::findOrFail($inventoryId), 'Inventory Updated successfully.', 202);
    }
}
