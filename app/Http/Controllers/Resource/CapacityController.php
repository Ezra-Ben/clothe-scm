<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\Batch; 
use App\Models\ResourceAssignment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CapacityController extends Controller
{
    public function index(Request $request)
    {
        $resources = Resource::all();
        $batches = Batch::where('status', '!=', 'completed')->get(); // Only non-completed batches

        // Date range for capacity view (e.g., next 7 days)
        $startDate = $request->input('start_date', Carbon::now()->startOfDay());
        $endDate = $request->input('end_date', Carbon::parse($startDate)->addDays(6)->endOfDay());

        $assignments = ResourceAssignment::whereBetween('assigned_start_time', [$startDate, $endDate])
                                        ->orWhereBetween('assigned_end_time', [$startDate, $endDate])
                                        ->orWhere(function ($query) use ($startDate, $endDate) {
                                            $query->where('assigned_start_time', '<=', $startDate)
                                                  ->where('assigned_end_time', '>=', $endDate);
                                        })
                                        ->with(['resource', 'batch'])
                                        ->get()
                                        ->groupBy('resource_id');

        // Prepare data for a calendar/timeline view
        $calendarData = [];
        foreach ($resources as $resource) {
            $calendarData[$resource->id] = [
                'resource' => $resource,
                'events' => [],
            ];
            if (isset($assignments[$resource->id])) {
                foreach ($assignments[$resource->id] as $assignment) {
                    $calendarData[$resource->id]['events'][] = [
                        'id' => $assignment->id,
                        'title' => 'Batch ' . ($assignment->batch->id ?? 'N/A') . ' - ' . $assignment->purpose,
                        'start' => $assignment->assigned_start_time->toIso8601String(),
                        'end' => $assignment->assigned_end_time->toIso8601String(),
                        'batch_id' => $assignment->batch_id,
                        'resource_id' => $assignment->resource_id,
                        // Add more data for styling/popups
                    ];
                }
            }
        }

        return view('resources.capacity_planning', compact('resources', 'batches', 'calendarData', 'startDate', 'endDate'));
    }

    public function assignResource(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'batch_id' => 'required|exists:batches,id',
            'purpose' => 'required|string',
            'assigned_start_time' => 'required|date',
            'assigned_end_time' => 'required|date|after_or_equal:assigned_start_time',
        ]);

        ResourceAssignment::create([
            'resource_id' => $request->resource_id,
            'batch_id' => $request->batch_id,
            'purpose' => $request->purpose,
            'assigned_start_time' => $request->assigned_start_time,
            'assigned_end_time' => $request->assigned_end_time,
            'expected_duration_minutes' => Carbon::parse($request->assigned_start_time)->diffInMinutes(Carbon::parse($request->assigned_end_time)),
            'status' => 'planned',
        ]);

        return redirect()->route('capacity_planning.index')->with('success', 'Resource assigned successfully.');
    }

}