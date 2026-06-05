<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Product::insert([
            [
                'name' => 'Modern Velvet Sofa',
                'description' => 'Comfortable 3-seater sofa, perfect for living rooms.',
                'price' => 45000,
                'category' => 'Furniture',
                'location' => 'Nairobi',
                'image_url' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=800',
            ],
            [
                'name' => 'Smart 50-inch TV',
                'description' => '4K Ultra HD smart television with Netflix integration.',
                'price' => 65000,
                'category' => 'Electronics',
                'location' => 'Nakuru',
                'image_url' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?q=80&w=800',
            ]
        ]);
    }
}
