<?php

namespace App\Http\Controllers\Production;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ResourceAssignment;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function resourceUtilization(Request $request)
    {
        $start = Carbon::parse($request->query('start_date', now()->subMonth()));
        $end = Carbon::parse($request->query('end_date', now()));

        $type = $request->query('resource_type');

        $resources = Resource::when($type, fn($q) => $q->where('type', $type))->get();

        $utilizationData = $resources->map(function($res) use ($start, $end) {
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
                'assigned_hours' => $assignedHours,
                'utilization_rate' => round($rate, 2),
                'status' => $res->status,
            ];
        })->toArray();

        return view('production.resources.report', compact('utilizationData', 'start', 'end'));
    }
}
