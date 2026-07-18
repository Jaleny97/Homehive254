<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;

class OrderTest extends TestCase
{
    public function test_user_can_create_order(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['quantity' => 100]);

        $response = $this->actingAs($user)
            ->postJson('/api/orders', [
                'items' => [
                    [
                        'product_id' => $product->id,
                        'quantity' => 2,
                    ],
                ],
                'shipping_address' => [
                    'street' => '123 Main St',
                    'city' => 'Test City',
                    'country' => 'Test Country',
                    'postal_code' => '12345',
                ],
                'payment_method' => 'card',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', ['user_id' => $user->id]);
    }

    public function test_user_can_view_their_orders(): void
    {
        $user = User::factory()->create();
        Order::factory(3)->for($user)->create();

        $response = $this->actingAs($user)
            ->getJson('/api/orders');

        $response->assertStatus(200);
    }

    public function test_user_cannot_view_others_orders(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $order = Order::factory()->for($user1)->create();

        $response = $this->actingAs($user2)
            ->getJson('/api/orders/' . $order->id);

        $response->assertStatus(403);
    }
}
