<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Rules\OrderItemsTypeRule;
use App\Rules\StockAvailabilityRule;
use App\Services\Inventory;
use App\Services\Item;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{ 
    /**
     * @var OrderRepository
     */
    protected OrderRepository $orderRepository;
    /**
     * @var Item
     */
    protected Item $orderItemsService;
    /**
     * @var Inventory
     */
    protected Inventory $inventoriesService;

    /**
     * @param OrderRepository $orderRepository
     * @param Item $orderItemsService
     * @param Inventory $inventoriesService
     */
    public function __construct(
        OrderRepository $orderRepository,
        Item $orderItemsService,
        Inventory $inventoriesService
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderItemsService = $orderItemsService;
        $this->inventoriesService = $inventoriesService;
    }

    /**
     * @param CreateOrderRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateOrderRequest $request)
    {   
        $request->validate([
            'products' => [
                new StockAvailabilityRule,
                new OrderItemsTypeRule
            ]
        ]);

        $order = $this->orderRepository->createOrder([
            'customer_id' => Auth::id(),
            'status' => Order::ORDER_STATUS_NEW
        ]);
        $items = $this->orderItemsService->saveOrderItems($order->id, $request['products']);
        $this->inventoriesService->modifyStock($items);
        return $this->successResponse(new OrderResource($order), 'Order placed successfully.');
    }
}
    