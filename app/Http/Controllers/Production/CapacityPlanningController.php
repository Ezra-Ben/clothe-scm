<?php

namespace App\Http\Controllers\Production;

use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\ProductionBatch;
use App\Models\ResourceAssignment;
use App\Http\Controllers\Controller;

class CapacityPlanningController extends Controller
{
    public function index(Request $request)
    {
        $start = Carbon::parse($request->query('start_date', now()->startOfMonth()));
        $end = Carbon::parse($request->query('end_date', now()->endOfMonth()));

        $resources = Resource::all();
        $batches = ProductionBatch::all();

        // Events formatted for FullCalendar
    $calendarEvents = ResourceAssignment::whereBetween('assigned_start_time', [$start, $end])
        ->with(['resource', 'batch'])
        ->get()
        ->map(function ($a) {
            return [
                'id' => "Batch-{$a->batch_id}-Assignment-{$a->id}",
                'title' => "Batch #{$a->batch_id}",
                'start' => $a->assigned_start_time->toIso8601String(),
                'end' => $a->assigned_end_time->toIso8601String(),
                'resourceId' => $a->resource_id,
            ];
        });

    // Resources formatted for FullCalendar
    $calendarResources = $resources->map(function ($r) {
        return [
            'id' => $r->id,
            'title' => $r->name . ' (' . ucfirst($r->type) . ')',
        ];
    })->toArray();

    return view('production.resources.assign', compact(
        'resources',
        'batches',
        'calendarEvents',
        'calendarResources',
        'start',
        'end'
    ));
    }

    public function assign(Request $request)
    {
        $data = $request->validate([
            'batch_id' => 'required|exists:production_batches,id',
            'resource_id' => 'required|exists:resources,id',
            'purpose' => 'required|string',
            'assigned_start_time' => 'required|date',
            'assigned_end_time' => 'required|date|after:assigned_start_time',
        ]);
        
        $resource = Resource::findOrFail($data['resource_id']);
        $batch = ProductionBatch::findOrFail($data['batch_id']);
    
        $start = Carbon::parse($data['assigned_start_time']);
        $end = Carbon::parse($data['assigned_end_time']);
        $durationMinutes = $start->diffInMinutes($end);
        $durationHours = $durationMinutes / 60;

        $capacityPerHour = $resource->capacity_units_per_hour ?? 0;

        if ($capacityPerHour <= 0) {
            return back()->withErrors([
                'resource_id' => "Resource has invalid capacity (0 units/hour)."
            ])->withInput();
        }

        // 1. Get overlapping assignments
        $overlappingAssignments = $resource->assignments()
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('assigned_start_time', [$start, $end])
                      ->orWhereBetween('assigned_end_time', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('assigned_start_time', '<', $start)
                            ->where('assigned_end_time', '>', $end);
                    });
            })
            ->with('batch') // eager-load batch to access produced_quantity
            ->get();

        // 2. Sum total quantity assigned in overlapping window
        $existingTotalQuantity = $overlappingAssignments->sum(function ($assignment) {
            return $assignment->batch->produced_quantity ?? 0;
        });

        // 3. Include new batch quantity
        $totalQuantity = $existingTotalQuantity + ($batch->produced_quantity ?? 0);
        $availableCapacity = $capacityPerHour * $durationHours;

        if ($totalQuantity > $availableCapacity) {
            return back()->withErrors([
                'resource_id' => "Over-capacity: Trying to assign total of {$totalQuantity} units, but resource can handle max {$availableCapacity} units during this window."
            ])->withInput();
        }

        // Save assignment
        ResourceAssignment::create([
            'resource_id' => $resource->id,
            'batch_id' => $batch->id,
            'purpose' => $data['purpose'],
            'assigned_start_time' => $start,
            'assigned_end_time' => $end,
            'expected_duration_minutes' => $durationMinutes,
            'status' => 'planned',
        ]);

        return redirect()->route('capacity_planning.index')->with('success', 'Resource assigned successfully.');
    }

    public function updateAssignment(Request $request)
    {
        $data = $request->validate([
            'assignment_id' => 'required|exists:resource_assignments,id',
            'resource_id' => 'required|exists:resources,id',
            'assigned_start_time' => 'required|date',
            'assigned_end_time' => 'required|date|after:assigned_start_time',
        ]);

        $assignment = ResourceAssignment::findOrFail($data['assignment_id']);
        $resource = Resource::findOrFail($data['resource_id']);

        $start = Carbon::parse($data['assigned_start_time']);
        $end = Carbon::parse($data['assigned_end_time']);
        $durationMinutes = $start->diffInMinutes($end);
        $durationHours = $durationMinutes / 60;

        $batch = $assignment->batch;
        $capacityPerHour = $resource->capacity_units_per_hour ?? 0;

        if ($capacityPerHour <= 0) {
            return response()->json([
                'success' => false,
                'message' => "Resource has 0 capacity.",
            ]);
        }

        // Check overlapping assignments (excluding this one)
        $overlappingAssignments = $resource->assignments()
            ->where('id', '!=', $assignment->id)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('assigned_start_time', [$start, $end])
                    ->orWhereBetween('assigned_end_time', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('assigned_start_time', '<', $start)
                        ->where('assigned_end_time', '>', $end);
                    });
            })
            ->with('batch')
            ->get();

        $existingQuantity = $overlappingAssignments->sum(function ($a) {
            return $a->batch->produced_quantity ?? 0;
        });

        $totalQuantity = $existingQuantity + ($batch->produced_quantity ?? 0);
        $availableCapacity = $capacityPerHour * $durationHours;

        if ($totalQuantity > $availableCapacity) {
            return response()->json([
                'success' => false,
                'message' => "Over capacity: {$totalQuantity} > {$availableCapacity}",
            ]);
        }

        $assignment->update([
            'resource_id' => $data['resource_id'],
            'assigned_start_time' => $start,
            'assigned_end_time' => $end,
            'expected_duration_minutes' => $durationMinutes,
        ]);

        return response()->json(['success' => true]);
    }

}
