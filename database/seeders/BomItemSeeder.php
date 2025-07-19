<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BomItem;

class BomItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        BomItem::insert([
            // T-Shirt
            ['bom_id' => 1, 'raw_material_id' => 1, 'quantity' => 1.2, 'unit_of_measure' => 'meter'],
            ['bom_id' => 1, 'raw_material_id' => 7, 'quantity' => 1, 'unit_of_measure' => 'spool'],
            ['bom_id' => 1, 'raw_material_id' => 9, 'quantity' => 1, 'unit_of_measure' => 'piece'],
            // Polo
            ['bom_id' => 2, 'raw_material_id' => 2, 'quantity' => 1.5, 'unit_of_measure' => 'meter'],
            ['bom_id' => 2, 'raw_material_id' => 5, 'quantity' => 5, 'unit_of_measure' => 'pack'],
            ['bom_id' => 2, 'raw_material_id' => 7, 'quantity' => 1, 'unit_of_measure' => 'spool'],
            ['bom_id' => 2, 'raw_material_id' => 9, 'quantity' => 1, 'unit_of_measure' => 'piece'],
            // Linen Shirt
            ['bom_id' => 3, 'raw_material_id' => 4, 'quantity' => 1.4, 'unit_of_measure' => 'meter'],
            ['bom_id' => 3, 'raw_material_id' => 5, 'quantity' => 6, 'unit_of_measure' => 'pack'],
            ['bom_id' => 3, 'raw_material_id' => 7, 'quantity' => 1, 'unit_of_measure' => 'spool'],
            ['bom_id' => 3, 'raw_material_id' => 9, 'quantity' => 1, 'unit_of_measure' => 'piece'],
            // Denim Jeans
            ['bom_id' => 4, 'raw_material_id' => 3, 'quantity' => 1.8, 'unit_of_measure' => 'meter'],
            ['bom_id' => 4, 'raw_material_id' => 6, 'quantity' => 1, 'unit_of_measure' => 'piece'],
            ['bom_id' => 4, 'raw_material_id' => 5, 'quantity' => 1, 'unit_of_measure' => 'pack'],
            ['bom_id' => 4, 'raw_material_id' => 7, 'quantity' => 1, 'unit_of_measure' => 'spool'],
            ['bom_id' => 4, 'raw_material_id' => 9, 'quantity' => 1, 'unit_of_measure' => 'piece'],
            // School Uniform Shirt
            ['bom_id' => 5, 'raw_material_id' => 1, 'quantity' => 1.3, 'unit_of_measure' => 'meter'],
            ['bom_id' => 5, 'raw_material_id' => 5, 'quantity' => 5, 'unit_of_measure' => 'pack'],
            ['bom_id' => 5, 'raw_material_id' => 7, 'quantity' => 1, 'unit_of_measure' => 'spool'],
            ['bom_id' => 5, 'raw_material_id' => 9, 'quantity' => 1, 'unit_of_measure' => 'piece'],
        ]);
    }
}
