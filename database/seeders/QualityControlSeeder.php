<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QualityControlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\QualityControl::insert([
            [
                'production_batch_id' => 1,
                'tester_id' => 1,
                'defects_found' => 'None',
                'status' => 'passed',
                'tested_at' => now()->subDays(8),
                'notes' => 'Batch passed all checks.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'production_batch_id' => 2,
                'tester_id' => 2,
                'defects_found' => 'Loose stitching on 2 items',
                'status' => 'failed',
                'tested_at' => now()->subDays(2),
                'notes' => 'Needs rework.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'production_batch_id' => 3,
                'tester_id' => 3,
                'defects_found' => null,
                'status' => 'pending',
                'tested_at' => null,
                'notes' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
