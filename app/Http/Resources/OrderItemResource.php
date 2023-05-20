<?php

namespace App\Http\Resources;

use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = Product::findOrFail($this->product_id);
        $conclusiveItem = OrderItem::find($this->conclusive_item_id);
        return [
            'id' => $this->id,
            'product' => new ProductResource($product),
            'conclusive_product' => isset($conclusiveItem) ? new ProductResource($conclusiveItem->product) : null,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
