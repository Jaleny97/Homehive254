<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class ProductTest extends TestCase
{
    public function test_can_get_all_products(): void
    {
        Product::factory(10)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'meta']);
    }

    public function test_can_search_products(): void
    {
        $product = Product::factory()->create([
            'name' => 'Premium Sofa',
        ]);

        $response = $this->getJson('/api/products?search=Premium');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'meta']);
    }

    public function test_can_filter_by_category(): void
    {
        $category = Category::factory()->create();
        Product::factory(5)->for($category)->create();

        $response = $this->getJson('/api/products?category_id=' . $category->id);

        $response->assertStatus(200);
    }

    public function test_seller_can_create_product(): void
    {
        $seller = User::factory()->seller()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($seller)
            ->postJson('/api/products', [
                'name' => 'Test Product',
                'description' => 'Test Description',
                'price' => 100,
                'category_id' => $category->id,
                'quantity' => 10,
            ]);

        $response->assertStatus(201);
    }

    public function test_customer_cannot_create_product(): void
    {
        $customer = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($customer)
            ->postJson('/api/products', [
                'name' => 'Test Product',
                'description' => 'Test Description',
                'price' => 100,
                'category_id' => $category->id,
                'quantity' => 10,
            ]);

        $response->assertStatus(403);
    }
}
