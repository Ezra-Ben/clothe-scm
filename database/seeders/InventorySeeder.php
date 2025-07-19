<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $inventories = [
            ['product_id' => 2, 'quantity_on_hand' => 50, 'quantity_reserved' => 5],
            ['product_id' => 3, 'quantity_on_hand' => 30, 'quantity_reserved' => 0],
            ['product_id' => 4, 'quantity_on_hand' => 100, 'quantity_reserved' => 10],
            ['product_id' => 5, 'quantity_on_hand' => 20, 'quantity_reserved' => 2],
            ['product_id' => 6, 'quantity_on_hand' => 40, 'quantity_reserved' => 0],
            ['product_id' => 7, 'quantity_on_hand' => 70, 'quantity_reserved' => 5],
            ['product_id' => 8, 'quantity_on_hand' => 15, 'quantity_reserved' => 1],
            ['product_id' => 9, 'quantity_on_hand' => 25, 'quantity_reserved' => 0],
            ['product_id' => 10, 'quantity_on_hand' => 35, 'quantity_reserved' => 0],
            ['product_id' => 11, 'quantity_on_hand' => 60, 'quantity_reserved' => 4],
            ['product_id' => 12, 'quantity_on_hand' => 45, 'quantity_reserved' => 0],
            ['product_id' => 13, 'quantity_on_hand' => 55, 'quantity_reserved' => 3],
            ['product_id' => 14, 'quantity_on_hand' => 20, 'quantity_reserved' => 0],
        ];

        foreach ($inventories as $data) {
            Inventory::updateOrCreate(
                ['product_id' => $data['product_id']],
                [
                    'quantity_on_hand' => $data['quantity_on_hand'],
                    'quantity_reserved' => $data['quantity_reserved'],
                ]
            );
        }
    }  

}
