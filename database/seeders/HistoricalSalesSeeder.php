<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoricalSalesSeeder extends Seeder
{
    public function run()
    {
        $path = 'C:\Users\SaCmi\Desktop\scm-ml\data\historical_sales.csv';

        $csv = array_map('str_getcsv', file($path));
        $header = array_shift($csv);

        foreach ($csv as $row) {
            $data = array_combine($header, $row);

            DB::table('historical_sales')->insert([
                'product_id' => $data['product_id'],
                'quantity'   => $data['quantity'],
                'date'       => $data['date'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
