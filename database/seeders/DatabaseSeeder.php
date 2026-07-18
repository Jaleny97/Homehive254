<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@homehive254.com',
            'password' => Hash::make('password123'),
            'phone' => '+1234567890',
            'address' => '123 Admin Street',
            'city' => 'Admin City',
            'country' => 'Admin Country',
            'postal_code' => '12345',
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create seller users
        $sellers = User::factory(5)->seller()->create();

        // Create customer users
        $customers = User::factory(20)->customer()->create();

        // Create categories
        $categories = Category::factory(8)->create();

        // Create products
        foreach ($sellers as $seller) {
            Product::factory(10)
                ->for($seller, 'seller')
                ->for($categories->random(), 'category')
                ->create();
        }

        // Create reviews
        $products = Product::all();
        foreach ($customers->take(10) as $customer) {
            foreach ($products->random(5) as $product) {
                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $customer->id,
                    'rating' => rand(1, 5),
                    'title' => fake()->sentence(),
                    'comment' => fake()->paragraph(),
                    'is_verified_purchase' => true,
                ]);
            }
        }

        echo "✅ Database seeded successfully!\n";
        echo "📊 Created:\n";
        echo "   - 1 Admin user (admin@homehive254.com)\n";
        echo "   - 5 Seller users\n";
        echo "   - 20 Customer users\n";
        echo "   - 8 Categories\n";
        echo "   - 50 Products\n";
    }
}
