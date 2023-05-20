<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OrderItemsTypeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $orderItems = $value;
        foreach ($orderItems as $index => $itemData) {
            $productType = Product::findOrFail($itemData['product_id'])->type_id;
            if ($index === 0 && $productType === Product::TYPE_INGREDIENT) {
                $fail('Can\'t place the order .. '.
                'the first order item should be a compound product which is followed by it\'s ingredients');
            }
        }
    }
}
