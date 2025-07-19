<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bom; 

class BomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bom::insert([
            ['product_id' => 2, 'version' => 'v1', 'description' => 'BOM for Classic Cotton T-Shirt'],
            ['product_id' => 3, 'version' => 'v1', 'description' => 'BOM for Organic Cotton Polo'],
            ['product_id' => 5, 'version' => 'v1', 'description' => 'BOM for Linen Blend Shirt'],
            ['product_id' => 6, 'version' => 'v1', 'description' => 'BOM for Denim Jeans'],
            ['product_id' => 11, 'version' => 'v1', 'description' => 'BOM for School Uniform Shirt'],
        ]);
    }
}
