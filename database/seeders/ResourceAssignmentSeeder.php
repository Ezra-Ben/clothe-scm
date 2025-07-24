<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;use App\Models\Resource;
use App\Models\ProductionBatch;
use App\Models\ResourceAssignment;
use Illuminate\Support\Carbon;

class ResourceAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $resources = Resource::all();
        $batches = ProductionBatch::all();

        if ($resources->count() < 4) {
            $this->command->warn('You need at least 4 resources to assign per batch.');
            return;
        }

        foreach ($batches as $batch) {
            // Randomly pick 3 or 4 resources to assign
            $assignedResources = $resources->random(rand(3, 4));

            foreach ($assignedResources as $resource) {
                // Use batch's start/completed time if available, otherwise fallback
                $start = $batch->started_at ? Carbon::parse($batch->started_at)->copy() : now()->copy()->subDays(3);
                $end = $batch->completed_at ? Carbon::parse($batch->completed_at)->copy() : $start->copy()->addHours(2);


                // Add a random offset to simulate realistic usage periods
                $shiftedStart = $start->copy()->addMinutes(rand(0, 60));
                $shiftedEnd = $shiftedStart->copy()->addMinutes(rand(45, 120));

                ResourceAssignment::create([
                    'resource_id' => $resource->id,
                    'batch_id' => $batch->id,
                    'purpose' => fake()->randomElement([
                        'cutting', 'sewing', 'finishing', 'pressing', 'embroidery', 'packaging'
                    ]),
                    'assigned_start_time' => $shiftedStart,
                    'assigned_end_time' => $shiftedEnd,
                    'expected_duration_minutes' => abs($shiftedEnd->diffInMinutes($shiftedStart)),
                    'status' => fake()->randomElement(['planned', 'active', 'completed']),
                ]);
            }
        }
    }
}
