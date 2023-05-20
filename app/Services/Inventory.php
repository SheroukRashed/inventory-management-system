<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\InventoryRepository;

class Inventory
{
    /**
     * Check if a given product has enough stock.
     * 
     * @param string $productId
     * @param float $orderedQuantity
     * @return boolean
     */
    public function isAvailable($productId, float $orderedQuantity)
    {
        $product = Product::findOrFail($productId);
        return $this->isAvailableIngredient($product, $orderedQuantity);
    }


    /**
     * Check if a given ingredient has enough stock.
     * 
     * @param Product $product
     * @param float $orderedQuantity
     * @return boolean
     */
    public function isAvailableIngredient(Product $product, float $orderedQuantity)
    {
        $productInventory = $product->inventory;
        $availableQty = $productInventory->left_in_stock_quantity;
        return $availableQty >= $orderedQuantity ? true : false;
    }

    /**
     * Modify inventory stock.
     * 
     * @param array $items
     */
    public function modifyStock($items)
    {
        foreach ($items as $item) {
            $productType = Product::findOrFail($item->product_id)->type_id;
            if ($productType === Product::TYPE_INGREDIENT) {
                $this->modifyIngredientStockByProductId($item->product_id, $item->quantity);
            } elseif ($productType === Product::TYPE_COMPOUND) {
                $this->modifyCompoundStockByProductId($item->product_id, $item->quantity);
            }
        }
    }

    /**
     * Modify inventory stock.
     * 
     * @param string $productId
     * @param float $orderedQuantity
     */
    public function modifyCompoundStockByProductId($productId, float $orderedQuantity)
    {
        $productIngredients = Product::findOrFail($productId)->ingredients;
        foreach ($productIngredients as $ingredient) {
            $this->modifyIngredientStockByProductId(
                $ingredient->ingredient_id, 
                $orderedQuantity * $ingredient->quantity
            );
        }
    }

    /**
     * Modify inventory stock.
     * 
     * @param string $productId
     * @param float $orderedQuantity
     */
    public function modifyIngredientStockByProductId($productId, float $orderedQuantity)
    {
        $inventoryRepository = new InventoryRepository();
        $productInventory = Product::findOrFail($productId)->inventory;
        $inventoryDetails = [
            'product_id' => $productInventory->product_id,
            'stock_id' => $productInventory->stock_id,
            'left_in_stock_quantity' => $productInventory->left_in_stock_quantity - $orderedQuantity,
            'threshold_quantity' => $productInventory->threshold_quantity,
            'is_limited'  => $productInventory->is_limited,
        ];
        $inventoryRepository->updateInventory($productInventory->id, $inventoryDetails);
    }
}
