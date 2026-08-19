<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@homehive254.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
            ]
        );

        // Sellers
        $sellers = [];
        for ($i = 1; $i <= 3; $i++) {
            $sellers[] = User::firstOrCreate(
                ['email' => "seller{$i}@homehive254.com"],
                [
                    'name' => "Seller {$i}",
                    'password' => Hash::make('password123'),
                ]
            );
        }

        // Categories
        $categories = [];
        $categoryNames = ['Furniture', 'Electronics', 'Home Decor', 'Kitchen', 'Outdoor'];
        foreach ($categoryNames as $name) {
            $categories[] = Category::firstOrCreate(['name' => $name], ['description' => "$name category"]);
        }

        // Products
        foreach ($sellers as $seller) {
            for ($p = 1; $p <= 5; $p++) {
                $cat = $categories[array_rand($categories)];
                Product::firstOrCreate([
                    'sku' => Str::upper('DEMO-' . uniqid()),
                ], [
                    'name' => "Demo Product {$p} by {$seller->name}",
                    'description' => "Demo description for product {$p}",
                    'price' => 100.00 + $p * 10,
                    'discount_price' => null,
                    'category_id' => $cat->id,
                    'seller_id' => $seller->id,
                    'quantity' => 10 * $p,
                    'image_url' => null,
                    'is_active' => true,
                ]);
            }
        }

        $this->command->info('✅ Demo data seeded. Admin: admin@homehive254.com (password: password123)');
    }
}
