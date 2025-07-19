<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\ProductionOrder;
use App\Models\ProductionBatch;
use App\Models\QualityControl;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductionSimulationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Add production batches + QC for existing 13 completed orders
for ($i = 2; $i <= 13; $i++) {
    $batch = ProductionBatch::create([
        'production_order_id' => $i,
        'produced_quantity'   => 60, // Match your orders if consistent
        'status'              => 'completed',
        'started_at'          => Carbon::now()->subDays(rand(10, 15)),
        'completed_at'        => Carbon::now()->subDays(rand(1, 5)),
    ]);

    QualityControl::create([
        'production_batch_id'       => $batch->id,
        'inspection_date'           => Carbon::now()->toDateString(),
        'defect_count'              => 0,
        'status'                    => 'passed',
        'notes'                     => null,
        'corrective_action_taken'   => null,
    ]);
}

// 2. Add new test data (5 production orders with mixed statuses)
$orders = [
    ['order_id' => null, 'product_id' => 2, 'quantity' => 20, 'status' => 'pending'],
    ['order_id' => null, 'product_id' => 3, 'quantity' => 30, 'status' => 'pending'],
    ['order_id' => null, 'product_id' => 4, 'quantity' => 15, 'status' => 'pending'],
    ['order_id' => null, 'product_id' => 5, 'quantity' => 25, 'status' => 'completed'],
    ['order_id' => null, 'product_id' => 6, 'quantity' => 10, 'status' => 'completed'],
];

$newOrders = [];
foreach ($orders as $data) {
    $newOrders[] = ProductionOrder::create($data);
}

// 3. Add corresponding batches and QC
$batches = [
    ['production_order_id' => $newOrders[0]->id, 'produced_quantity' => 20, 'status' => 'completed'],
    ['production_order_id' => $newOrders[1]->id, 'produced_quantity' => 30, 'status' => 'completed'],
    ['production_order_id' => $newOrders[2]->id, 'produced_quantity' => 15, 'status' => 'pending'],
    ['production_order_id' => $newOrders[3]->id, 'produced_quantity' => 25, 'status' => 'completed'],
    ['production_order_id' => $newOrders[4]->id, 'produced_quantity' => 10, 'status' => 'completed'],
];

foreach ($batches as $data) {
    $data['started_at'] = Carbon::now()->subDays(2);
    $data['completed_at'] = $data['status'] === 'completed' ? Carbon::now()->subDay() : null;

    $batch = ProductionBatch::create($data);

    if ($batch->status === 'completed') {
        $relatedOrder = $batch->productionOrder;
        $qcResult = $relatedOrder->status === 'pending' ? 'failed' : 'passed';

        QualityControl::create([
            'production_batch_id'     => $batch->id,
            'inspection_date'         => Carbon::now()->toDateString(),
            'defect_count'            => $qcResult === 'failed' ? rand(1, 5) : 0,
            'status'                  => $qcResult,
            'notes'                   => $qcResult === 'failed' ? 'Produced before order status updated' : null,
            'corrective_action_taken' => $qcResult === 'failed' ? 'Manager alerted for early production' : null,
        ]);
    }
}
    }
}
