<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductionBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ProductionBatch::insert([
            [
                'batch_number' => 'BATCH-001',
                'product_id' => 1,
                'quantity' => 50,
                'status' => 'completed',
                'started_at' => now()->subDays(10),
                'completed_at' => now()->subDays(8),
                'notes' => 'First batch of blue t-shirts.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_number' => 'BATCH-002',
                'product_id' => 2,
                'quantity' => 30,
                'status' => 'in_progress',
                'started_at' => now()->subDays(5),
                'completed_at' => null,
                'notes' => 'Classic jeans batch.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'batch_number' => 'BATCH-003',
                'product_id' => 3,
                'quantity' => 20,
                'status' => 'pending',
                'started_at' => null,
                'completed_at' => null,
                'notes' => 'Red hoodies batch.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
