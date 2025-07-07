<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::insert([
            [
                'name' => 'Blue T-Shirt',
                'description' => 'A comfortable blue t-shirt made from 100% natural fibers.',
                'sku' => 'TSHIRT-BLUE-COTTON',
                'price' => 19.99,
                'stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'White Shirt',
                'description' => 'Classic white shirt crafted from soft material.',
                'sku' => 'SHIRT-WHITE-COTTON',
                'price' => 24.99,
                'stock' => 80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Polo',
                'description' => 'Smart casual polo shirt made of pure fabric.',
                'sku' => 'POLO-COTTON',
                'price' => 22.99,
                'stock' => 60,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hoodie',
                'description' => 'Warm and cozy hoodie.',
                'sku' => 'HOODIE-COTTON',
                'price' => 29.99,
                'stock' => 75,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dress',
                'description' => 'Lightweight summer dress.',
                'sku' => 'DRESS-COTTON',
                'price' => 34.99,
                'stock' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shorts',
                'description' => 'Breathable shorts for everyday wear.',
                'sku' => 'SHORTS-COTTON',
                'price' => 17.99,
                'stock' => 90,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Skirt',
                'description' => 'Elegant skirt made from soft fabric.',
                'sku' => 'SKIRT-COTTON',
                'price' => 27.99,
                'stock' => 50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pajamas',
                'description' => "Comfortable pajamas for a good night's sleep.",
                'sku' => 'PAJAMAS-COTTON',
                'price' => 21.99,
                'stock' => 70,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tank Top',
                'description' => 'Light and airy tank top.',
                'sku' => 'TANKTOP-COTTON',
                'price' => 14.99,
                'stock' => 110,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sweatpants',
                'description' => 'Relaxed fit sweatpants.',
                'sku' => 'SWEATPANTS-COTTON',
                'price' => 26.99,
                'stock' => 65,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
