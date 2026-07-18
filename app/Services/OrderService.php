<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderService
{
    /**
     * Create order from cart items
     */
    public function createOrder(User $user, array $data): Order
    {
        return DB::transaction(function () use ($user, $data) {
            // Validate all products exist and have sufficient stock
            $items = $data['items'];
            $subtotal = 0;
            $productData = [];

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->quantity < $item['quantity']) {
                    throw new Exception(
                        "Insufficient stock for {$product->name}. Available: {$product->quantity}"
                    );
                }

                $itemTotal = $product->discount_price ?? $product->price * $item['quantity'];
                $subtotal += $itemTotal;
                $productData[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->discount_price ?? $product->price,
                    'total' => $itemTotal,
                ];
            }

            // Calculate taxes and shipping
            $tax = $subtotal * 0.1; // 10% tax
            $shipping = $subtotal > 100 ? 0 : 10; // Free shipping over $100
            $total = $subtotal + $tax + $shipping;

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'shipping_address' => $data['shipping_address'],
                'billing_address' => $data['billing_address'] ?? $data['shipping_address'],
                'notes' => $data['notes'] ?? null,
            ]);

            // Create order items and update product stock
            foreach ($productData as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                ]);

                // Reduce product stock
                $item['product']->decrement('quantity', $item['quantity']);
            }

            return $order;
        });
    }
}
