<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\OrderItemRepository;

class Item
{
    protected string $conclusiveItemId;
    protected array $items;

    /**
     * save order Items.
     * 
     * @param string $orderId
     * @param mixed $orderItemsPayload
     */
    public function saveOrderItems($orderId, $orderItemsPayload)
    {
        foreach ($orderItemsPayload as $itemData) {
            $items[] = $this->saveOrderItemByProductId($orderId, $itemData['product_id'], $itemData['quantity']);
        }
        return $items;
    }

    /**
     * save each order Item.
     * 
     * @param string $orderId
     * @param string $prdoductId
     * @param string $orderedQuantity
     */
    public function saveOrderItemByProductId($orderId, $prdoductId, $orderedQuantity)
    {
        $orderItemsRepository = new OrderItemRepository();
        $productType = Product::findOrFail($prdoductId)->type_id;
        if ($productType === Product::TYPE_COMPOUND) {
            $orderItemDetails = $this->prepareCompoundItemsDetails($orderId, $prdoductId, $orderedQuantity);
            $createdItem = $orderItemsRepository->createItem($orderItemDetails);
            $this->conclusiveItemId = $createdItem->id;
        } else {
            $orderItemDetails = $this->prepareIngredientItemsDetails($orderId, $prdoductId, $orderedQuantity);
            $createdItem = $orderItemsRepository->createItem($orderItemDetails);
        }
        return $createdItem;
    }

    /**
     * prepare compound order Items.
     * 
     * @param string $orderId
     * @param string $prdoductId
     * @param string $orderedQuantity
     */
    public function prepareCompoundItemsDetails($orderId, $prdoductId, $orderedQuantity)
    {
        return [
            'order_id' => $orderId,
            'product_id' => $prdoductId,
            'conclusive_item_id' => null,
            'quantity' => $orderedQuantity
        ];
    }

    /**
     * prepare ingredient order Items.
     * 
     * @param string $orderId
     * @param string $prdoductId
     * @param string $orderedQuantity
     */
    public function prepareIngredientItemsDetails($orderId, $prdoductId, $orderedQuantity)
    {
        return [
            'order_id' => $orderId,
            'product_id' => $prdoductId,
            'conclusive_item_id' => $this->conclusiveItemId,
            'quantity' => $orderedQuantity
        ];
    }
}
