<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class GenerateForecasts extends Command
{
    protected $signature = 'app:generate-forecasts {--month=}'; //(YYYY-MM)
    protected $description = 'Generate product forecasts for next month (or specified month) and save to the forecasts table';

    public function handle()
    {
        //allow user to specify forecast month (YYYY-MM)
        $specifiedMonth = $this->option('month');

        // Step 1: Get latest month with data per product
        $latestDataPerProduct = DB::select("
            WITH combined_sales AS (
                SELECT
                    product_id,
                    DATE_FORMAT(date, '%Y-%m-01') AS month,
                    SUM(quantity) AS total_quantity
                FROM historical_sales
                GROUP BY product_id, month

                UNION ALL

                SELECT
                    product_id,
                    DATE_FORMAT(created_at, '%Y-%m-01') AS month,
                    SUM(quantity) AS total_quantity
                FROM order_items
                GROUP BY product_id, month
            ),
            latest_month_per_product AS (
                SELECT
                    product_id,
                    MAX(month) AS latest_month
                FROM combined_sales
                GROUP BY product_id
            ),
            features AS (
                SELECT
                    cs.product_id,
                    cs.month,
                    cs.total_quantity,
                    LAG(cs.total_quantity, 1) OVER (PARTITION BY cs.product_id ORDER BY cs.month) AS prev_quantity,
                    AVG(cs.total_quantity) OVER (
                        PARTITION BY cs.product_id
                        ORDER BY cs.month
                        ROWS BETWEEN 2 PRECEDING AND CURRENT ROW
                    ) AS rolling_mean_3
                FROM combined_sales cs
            )
            SELECT
                f.product_id,
                f.month,
                f.prev_quantity,
                f.rolling_mean_3,
                pm.model_product_code
            FROM features f
            JOIN latest_month_per_product lmp ON f.product_id = lmp.product_id AND f.month = lmp.latest_month
            JOIN product_model_map pm ON f.product_id = pm.product_id
            WHERE f.prev_quantity IS NOT NULL
        ");

        $codeMap = [
            'P001' => 0,
            'P002' => 1,
            'P003' => 2,
            'P004' => 3,
            'P005' => 4,
            'P006' => 5,
            'P007' => 6,
            'P008' => 7,
            'P009' => 8,
            'P010' => 9,
            'P011' => 10,
            'P012' => 11,
            'P013' => 12,
        ];

        foreach ($latestDataPerProduct as $row) {
            $productCode = $codeMap[$row->model_product_code] ?? null;

            if ($productCode === null) {
                $this->error("No product code for {$row->model_product_code}");
                continue;
            }

            // Calculate forecast month: either specified or next month after latest
            if ($specifiedMonth) {
                try {
                    $forecastMonth = Carbon::parse($specifiedMonth)->startOfMonth();
                } catch (\Exception $e) {
                    $this->error("Invalid --month option format. Use YYYY-MM.");
                    return 1;
                }
            } else {
                $forecastMonth = Carbon::parse($row->month)->addMonthNoOverflow()->startOfMonth();
            }

            $futureMonthCode = intval($forecastMonth->format('n')); // 1-12

            // Skip if forecast already exists for this product and forecast_month
            $exists = DB::table('forecasts')
                ->where('product_id', $row->product_id)
                ->where('forecast_month', $forecastMonth->toDateString())
                ->exists();

            if ($exists) {
                $this->warn("Forecast already exists for product {$row->product_id} for {$forecastMonth->format('F Y')}, skipping.");
                continue;
            }

            try {
                $response = Http::timeout(10)->post('http://localhost:8001/predict_demand', [
                    'month_code' => $futureMonthCode,
                    'product_code' => $productCode,
                    'prev_quantity' => floatval($row->prev_quantity),
                    'rolling_mean_3' => floatval($row->rolling_mean_3),
                ]);

                $predictedQuantity = $response->json()['predicted_quantity'] ?? null;

            } catch (\Exception $e) {
                $this->error("API call failed: " . $e->getMessage());
                continue;
            }

            if ($predictedQuantity === null) {
                $this->error("Prediction failed for product ID {$row->product_id}");
                continue;
            }

            DB::table('forecasts')->insert([
                'product_id' => $row->product_id,
                'forecast_month' => $forecastMonth->toDateString(),
                'month_code' => $futureMonthCode,
                'predicted_quantity' => intval($predictedQuantity),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info("Forecast saved: product {$row->product_id} → {$predictedQuantity} for {$forecastMonth->format('F Y')}");
        }

        $this->info('All forecasts generated and saved.');
    }
}
