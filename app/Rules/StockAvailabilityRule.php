<?php

namespace App\Rules;

use App\Models\Product;
use App\Services\Inventory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StockAvailabilityRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $inventoryService = new Inventory();
        $products = $this->prepareData($value);
        foreach ($products as $productId => $orderedQty) {
            if (!$inventoryService->isAvailable($productId, $orderedQty)) {
                $fail('Can\'t place the order .. no available stock');
            }
        }
    }

    /**
     * The request is an array of objects; each object consists of 2 attributes (quantity, product_id)
     * this function is responsible for summing quantity in case of product_id repetition 
     * 
     * @param array $productsArrayOfObjects
     */
    private function prepareData($productsArrayOfObjects)
    {
        $uniqueProducts = [];
        foreach ($productsArrayOfObjects as $productObject) {
            $productType = Product::findOrFail($productObject['product_id'])->type_id;
            if ($productType === Product::TYPE_COMPOUND) {
                $uniqueProducts = $this->prepareDataCompound($productObject, $uniqueProducts);
            } else {
                $uniqueProducts = $this->prepareDataIngredient($productObject, $uniqueProducts);
            }
            
        }
        return $uniqueProducts;
    }

    /**
     * @param mixed $productObject
     * @param array $uniqueProducts
     * 
     * @return array
     */
    private function prepareDataCompound($productObject, $uniqueProducts) 
    {
        $compoundProductId = $productObject['product_id'];
        $compoundProductOrderedQuantity = $productObject['quantity'];
        $ingredients = Product::findOrFail($compoundProductId)->ingredients;
        foreach ($ingredients as $ingredient) {
            $productObject['product_id'] = $ingredient->ingredient_id;
            $productObject['quantity'] = $compoundProductOrderedQuantity * $ingredient->quantity;
            $uniqueProducts = $this->prepareDataIngredient($productObject, $uniqueProducts);
        }
        return $uniqueProducts;
    }

    /**
     * @param mixed $productObject
     * @param array $uniqueProducts
     * 
     * @return array
     */
    private function prepareDataIngredient($productObject, $uniqueProducts) 
    {
        $productId = $productObject['product_id'];
        $quantity = $productObject['quantity'];
        if (isset($uniqueProducts[$productId])) {
            $uniqueProducts[$productId] += $quantity;
        } else {
            $uniqueProducts[$productId] = $quantity;
        }
        return $uniqueProducts;
    }
}
