<?php

namespace App\Observers;

use App\Mail\LimitedStockEmail;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;

class InventoryObserver
{
    /**
     * Handle the Inventory "updating" event.
     * 
     * @param Inventory $inventory
     * 
     * @return void
     */
    public function updating(Inventory $inventory): void
    {
        $systemConfigs = config('system');
        $email = $systemConfigs['seller_email'];
        $product = $inventory->product;
        if ((bool)$inventory->is_limited && 
            !(bool)$inventory->getOriginal('is_limited') &&
            $product->type_id === Product::TYPE_INGREDIENT
        ) {
            $data = [
                'inventoryId' => $inventory->id,
                'productName' => $product->name
            ];
            Mail::to($email)->send(new LimitedStockEmail(compact('data')));
        }
    }
}
