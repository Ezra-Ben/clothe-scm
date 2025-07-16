<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SegmentCustomers extends Command
{
    protected $signature = 'app:segment-customers';
    protected $description = 'Segment all customers, send features to ML API, and store segment_id in customer_segments';

    public function handle()
    {
        // Get customer behavioral features
        $customers = DB::select("
            SELECT
                c.id AS customer_id,
                COALESCE(SUM(o.total), 0) AS total_spent,
                COALESCE(AVG(o.total), 0) AS avg_order_value,
                COUNT(DISTINCT o.id) AS order_frequency,
                COUNT(DISTINCT oi.product_id) AS product_variety,
                DATEDIFF(CURRENT_DATE, MAX(o.created_at)) AS last_purchase_days_ago
            FROM customers c
            LEFT JOIN orders o ON o.customer_id = c.id AND o.status = 'paid'
            LEFT JOIN order_items oi ON oi.order_id = o.id
            GROUP BY c.id
        ");

        foreach ($customers as $row) {
            try {
                $response = Http::timeout(10)->post('http://localhost:8001/recommend', [
                    'total_spent' => floatval($row->total_spent),
                    'avg_order_value' => floatval($row->avg_order_value),
                    'order_frequency' => intval($row->order_frequency),
                    'product_variety' => intval($row->product_variety),
                    'last_purchase_days_ago' => intval($row->last_purchase_days_ago),
                ]);

                $cluster_id = $response->json()['cluster_id'] ?? null;

                if ($cluster_id === null) {
                    $this->warn("No cluster_id for customer {$row->customer_id}");
                    continue;
                }

                // Insert new segment record
                DB::table('customer_segments')->insert([
                    'customer_id' => $row->customer_id,
                    'segment_id' => $cluster_id,
                    'generated_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $this->info("Customer {$row->customer_id} segmented → {$cluster_id}");
            } catch (\Exception $e) {
                $this->error("API error for customer {$row->customer_id}: " . $e->getMessage());
                continue;
            }
        }

        $this->info('Customer segmentation complete.');
    }
}
