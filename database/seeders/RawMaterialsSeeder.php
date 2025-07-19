<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RawMaterial;

class RawMaterialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RawMaterial::insert([
            ['name' => 'Cotton Fabric Roll**', 'sku' => 'CF-001', 'unit_of_measure' => 'meter', 'quantity_on_hand' => 5000, 'reorder_point' => 500, 'supplier_id' => 1],
            ['name' => 'Organic Cotton Roll', 'sku' => 'CF-002', 'unit_of_measure' => 'meter', 'quantity_on_hand' => 3000, 'reorder_point' => 300, 'supplier_id' => 1],
            ['name' => 'Denim Fabric Roll', 'sku' => 'DF-001', 'unit_of_measure' => 'meter', 'quantity_on_hand' => 2000, 'reorder_point' => 200, 'supplier_id' => 1],
            ['name' => 'Linen Fabric Roll', 'sku' => 'LF-001', 'unit_of_measure' => 'meter', 'quantity_on_hand' => 1000, 'reorder_point' => 100, 'supplier_id' => 1],
            ['name' => 'Buttons Pack', 'sku' => 'BTN-001', 'unit_of_measure' => 'pack', 'quantity_on_hand' => 1000, 'reorder_point' => 100, 'supplier_id' => 2],
            ['name' => 'Metallic Zi6pper', 'sku' => 'ZIP-001', 'unit_of_measure' => 'piece', 'quantity_on_hand' => 500, 'reorder_point' => 50, 'supplier_id' => 2],
            ['name' => 'Polyester Thread Spool', 'sku' => 'THR-001', 'unit_of_measure' => 'spool', 'quantity_on_hand' => 2000, 'reorder_point' => 200, 'supplier_id' => 2],
            ['name' => 'Elastic Band', 'sku' => 'ELB-001', 'unit_of_measure' => 'meter', 'quantity_on_hand' => 1000, 'reorder_point' => 100, 'supplier_id' => 2],
            ['name' => 'Packaging Bag', 'sku' => 'PKG-001', 'unit_of_measure' => 'piece', 'quantity_on_hand' => 5000, 'reorder_point' => 500, 'supplier_id' => 3],
        ]);
    }
}
