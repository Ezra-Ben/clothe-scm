<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{

public function run(): void
{
    $products = [
        [
            'name' => 'Classic Cotton T-Shirt',
            'description' => '100% cotton unisex round neck t-shirt, breathable and comfortable',
            'image' => 'tshirts.webp',
            'price' => 35000,
            'discount_percent' => 0,
        ],
        [
            'name' => 'Organic Cotton Polo',
            'description' => 'Premium polo shirt made from organic cotton',
            'image' => 'organic.jpg',
            'price' => 50000,
            'discount_percent' => 10,
        ],
        [
            'name' => 'Ankara Fabric (2 Yards)',
            'description' => 'Vibrant African print fabric, 2 yards',
            'image' => 'ankara.jpg',
            'price' => 25000,
            'discount_percent' => 5,
        ],
        [
            'name' => 'Linen Blend Shirt',
            'description' => 'Casual linen blend shirt for men',
            'image' => 'linen.jpg',
            'price' => 65000,
            'discount_percent' => 15,
        ],
        [
            'name' => 'Denim Jeans',
            'description' => 'Regular fit men’s denim jeans',
            'image' => 'denim.webp',
            'price' => 80000,
            'discount_percent' => 0,
        ],
        [
            'name' => 'Khanga Wrapper',
            'description' => 'Traditional colorful kitenge wrapper',
            'image' => 'khanga.jpg',
            'price' => 20000,
            'discount_percent' => 0,
        ],
        [
            'name' => 'Bed Sheets Set (4 pcs)',
            'description' => 'Double bed sheets with pillowcases',
            'image' => 'bedsheets.jpg',
            'price' => 120000,
            'discount_percent' => 20,
        ],
        [
            'name' => 'Bath Towels Set',
            'description' => 'Pack of 3 soft cotton towels',
            'image' => 'towels.jpg',
            'price' => 45000,
            'discount_percent' => 10,
        ],
        [
            'name' => 'Baby Blanket',
            'description' => 'Warm fleece baby blanket',
            'image' => 'baby_blanket.jpg',
            'price' => 35000,
            'discount_percent' => 5,
        ],
        [
            'name' => 'School Uniform Shirt',
            'description' => 'Boys\' short sleeve uniform shirt',
            'image' => 'uniform.jpg',
            'price' => 28000,
            'discount_percent' => 0,
        ],
        [
            'name' => 'Suit Fabric (2 meters)',
            'description' => 'High-quality suiting material',
            'image' => 'suit_fabric.webp',
            'price' => 90000,
            'discount_percent' => 10,
        ],
        [
            'name' => 'Curtain Fabric',
            'description' => 'Modern curtain fabric per meter',
            'image' => 'curtain.jpg',
            'price' => 30000,
            'discount_percent' => 0,
        ],
        [
            'name' => 'Upholstery Fabric',
            'description' => 'Heavy-duty fabric for furniture',
            'image' => 'upholstery.webp',
            'price' => 75000,
            'discount_percent' => 5,
        ],
    ];

    foreach ($products as $product) {
        Product::create($product);
    }
}

}
