<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummyHistoricalSalesSeeder extends Seeder
{
    public function run()
    {
        $productIds = DB::table('historical_sales')->distinct()->pluck('product_id');

        $start = Carbon::create(2024, 1, 1);
        $end = Carbon::create(2025, 6, 1);

        $quantityRanges = [
            [20, 30],
            [40, 50],
            [60, 80],
        ];

        foreach ($productIds as $productId) {
            $current = $start->copy();

            while ($current->lte($end)) {
                $monthStart = $current->copy()->startOfMonth()->toDateString();
                $monthEnd = $current->copy()->endOfMonth()->toDateString();

                $exists = DB::table('historical_sales')
                    ->where('product_id', $productId)
                    ->whereBetween('date', [$monthStart, $monthEnd])
                    ->exists();

                if (!$exists) {
                    $range = $quantityRanges[array_rand($quantityRanges)];
                    $quantity = rand($range[0], $range[1]);

                    $randomDay = rand(1, $current->daysInMonth);
                    $randomDate = $current->copy()->day($randomDay)->toDateString();

                    DB::table('historical_sales')->insert([
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'date' => $randomDate,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $current->addMonth();
            }
        }

        $this->command->info('Dummy historical sales inserted for missing months.');
    }
}
