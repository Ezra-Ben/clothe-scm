<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductModelMapSeeder extends Seeder
{
    public function run()
    {
        $map = [
            ['model_product_code' => 'P001', 'product_id' => 2],
            ['model_product_code' => 'P002', 'product_id' => 3],
            ['model_product_code' => 'P003', 'product_id' => 4],
            ['model_product_code' => 'P004', 'product_id' => 5],
            ['model_product_code' => 'P005', 'product_id' => 6],
            ['model_product_code' => 'P006', 'product_id' => 7],
            ['model_product_code' => 'P007', 'product_id' => 8],
            ['model_product_code' => 'P008', 'product_id' => 9],
            ['model_product_code' => 'P009', 'product_id' => 10],
            ['model_product_code' => 'P010', 'product_id' => 11],
            ['model_product_code' => 'P011', 'product_id' => 12],
            ['model_product_code' => 'P012', 'product_id' => 13],
            ['model_product_code' => 'P013', 'product_id' => 14],
            
        ];

        foreach ($map as $row) {
            DB::table('product_model_map')->insert($row);
        }
    }
}
