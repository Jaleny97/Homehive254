<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function dashboard(): JsonResponse
    {
        $this->authorize('isAdmin');

        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_categories' => Category::count(),
            'total_revenue' => Order::where('payment_status', 'completed')->sum('total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'recent_orders' => Order::with('user')->orderBy('created_at', 'desc')->limit(5)->get(),
        ];

        return response()->json($stats, 200);
    }

    /**
     * Get all users
     */
    public function users(Request $request): JsonResponse
    {
        $this->authorize('isAdmin');

        $users = User::when($request->search, function ($q) {
            return $q->where('name', 'like', '%' . request('search') . '%')
                     ->orWhere('email', 'like', '%' . request('search') . '%');
        })
        ->paginate($request->per_page ?? 15);

        return response()->json($users, 200);
    }

    /**
     * Deactivate user
     */
    public function deactivateUser(User $user): JsonResponse
    {
        $this->authorize('isAdmin');

        $user->update(['is_active' => false]);

        return response()->json(['message' => 'User deactivated successfully'], 200);
    }

    /**
     * Activate user
     */
    public function activateUser(User $user): JsonResponse
    {
        $this->authorize('isAdmin');

        $user->update(['is_active' => true]);

        return response()->json(['message' => 'User activated successfully'], 200);
    }

    /**
     * Get all orders
     */
    public function orders(Request $request): JsonResponse
    {
        $this->authorize('isAdmin');

        $orders = Order::with('user', 'items')
            ->when($request->status, function ($q) {
                return $q->where('status', request('status'));
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json($orders, 200);
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Order $order, Request $request): JsonResponse
    {
        $this->authorize('isAdmin');

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update($validated);

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order,
        ], 200);
    }
}
