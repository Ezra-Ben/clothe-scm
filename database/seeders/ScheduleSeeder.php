<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Schedule;
use App\Models\ProductionBatch;
use Illuminate\Support\Carbon;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Get all batches sorted by start date
            $batches = ProductionBatch::orderBy('started_at')->get();

            // Define a threshold for grouping (e.g. 7 days)
            $grouped = $batches->groupBy(function ($batch) {
                return $batch->started_at->format('Y-m-d'); // group by day
            });

            foreach ($grouped as $date => $batchGroup) {
                $start = $batchGroup->min('started_at');
                $end = $batchGroup->max('started_at')->addDays(3);

                $schedule = Schedule::create([
                    'start_date' => $start,
                    'end_date' => $end,
                    'status' => 'completed',
                ]);

                foreach ($batchGroup as $batch) {
                    $batch->schedule_id = $schedule->id;
                    $batch->save();
                }
            }
        });
    }
}
