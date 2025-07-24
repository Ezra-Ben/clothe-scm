<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Resource;

class GenerateResourceUtilizationReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-resource-utilization-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    use App\Models\Resource;
use App\Models\ResourceAssignment;
use Illuminate\Support\Carbon;

public function handle()
{
    // Set default date range: past 7 days
    $start = Carbon::now()->subWeek();
    $end = Carbon::now();

    $resources = Resource::when($type, fn($q) => $q->where('type', $type))->get();

    $utilizationData = $resources->map(function ($res) use ($start, $end) {
        $availableHours = $start->diffInHours($end);

        $assignedMinutes = ResourceAssignment::where('resource_id', $res->id)
            ->whereBetween('assigned_start_time', [$start, $end])
            ->sum('expected_duration_minutes');

        $assignedHours = $assignedMinutes / 60;

        $rate = $availableHours
            ? ($assignedHours / $availableHours) * 100
            : 0;

        return [
            'name' => $res->name,
            'type' => $res->type,
            'available_hours' => $availableHours,
            'assigned_hours' => round($assignedHours, 2),
            'utilization_rate' => round($rate, 2),
            'status' => $res->status,
        ];
    })->toArray();

    // Generate the PDF
    $pdf = app('dompdf.wrapper');
    $pdf->loadView('reports.resource_utilization', [
        'utilizationData' => $utilizationData,
        'start' => $start,
        'end' => $end
    ]);

    $fileName = 'resource_utilization_' . now()->format('Y-m-d_H-i-s') . '.pdf';
    $pdf->save(storage_path("app/reports/{$fileName}"));

    $this->info("Resource utilization report saved as {$fileName}");
}

}
