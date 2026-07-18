<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService) {}

    /**
     * Get user's orders
     */
    public function index(): JsonResponse
    {
        $orders = auth()->user()->orders()
            ->with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($orders, 200);
    }

    /**
     * Get order by ID
     */
    public function show(Order $order): JsonResponse
    {
        $this->authorize('view', $order);

        $order->load('items.product', 'payment');

        return response()->json($order, 200);
    }

    /**
     * Create order from cart
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            $order = $this->orderService->createOrder(
                auth()->user(),
                $request->validated()
            );

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order->load('items.product'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order): JsonResponse
    {
        $this->authorize('update', $order);

        if (!in_array($order->status, ['pending', 'processing'])) {
            return response()->json([
                'message' => 'Order cannot be cancelled in current status',
            ], 400);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Order cancelled successfully',
            'order' => $order,
        ], 200);
    }

    /**
     * Get order statistics (Admin only)
     */
    public function statistics(): JsonResponse
    {
        $this->authorize('isAdmin');

        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'delivered')->count(),
            'total_revenue' => Order::where('payment_status', 'completed')->sum('total'),
            'average_order_value' => Order::where('payment_status', 'completed')->avg('total'),
        ];

        return response()->json($stats, 200);
    }
}
