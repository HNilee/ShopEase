<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            ['name' => 'Valorant Account Gold', 'price' => 250000, 'category' => 'Game Accounts', 'image' => 'https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=800&auto=format&fit=crop'],
            ['name' => 'MLBB Account Epic', 'price' => 180000, 'category' => 'Game Accounts', 'image' => 'https://images.unsplash.com/photo-1605902711622-cfb43c9a3587?q=80&w=800&auto=format&fit=crop'],
            ['name' => 'PUBG UC Top-Up 300', 'price' => 75000, 'category' => 'Top-Up & Currency', 'image' => 'https://images.unsplash.com/photo-1611171719637-5e05c7f4a99a?q=80&w=800&auto=format&fit=crop'],
            ['name' => 'Steam Wallet 100k', 'price' => 100000, 'category' => 'Gift Cards', 'image' => 'https://images.unsplash.com/photo-1548943487-a2e4e43b4853?q=80&w=800&auto=format&fit=crop'],
            ['name' => 'Fortnite Skin Bundle', 'price' => 220000, 'category' => 'Skins & Items', 'image' => 'https://images.unsplash.com/photo-1550745165-9d05b1e9f63e?q=80&w=800&auto=format&fit=crop'],
            ['name' => 'Gaming Headset', 'price' => 450000, 'category' => 'Accessories', 'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=800&auto=format&fit=crop'],
        ];

        foreach ($samples as $s) {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($s['category'])],
                ['name' => $s['category']]
            );

            Product::firstOrCreate(
                ['slug' => Str::slug($s['name'])],
                [
                    'name' => $s['name'],
                    'category_id' => $category->id,
                    'sku' => strtoupper(Str::random(8)),
                    'description' => 'Produk game e-commerce.',
                    'price' => $s['price'],
                    'stock' => 100,
                    'image' => $s['image'],
                    'is_active' => true,
                ]
            );
        }
    }
}

