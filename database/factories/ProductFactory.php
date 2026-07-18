<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'discount_price' => $this->faker->optional()->randomFloat(2, 5, 400),
            'category_id' => Category::factory(),
            'seller_id' => User::factory()->seller(),
            'quantity' => $this->faker->numberBetween(0, 1000),
            'sku' => 'PROD-' . strtoupper($this->faker->unique()->bothify('??????')),
            'image_url' => $this->faker->imageUrl(),
            'is_active' => true,
            'is_featured' => false,
        ];
    }

    public function featured(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }
}
