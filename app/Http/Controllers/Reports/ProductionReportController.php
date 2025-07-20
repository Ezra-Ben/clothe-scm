<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ResourceAssignment;
use App\Models\Resource;
use App\Models\Batch;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductionReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function productionSummary(Request $request)
    {
        // Default filters
        $startDate = $request->input('start_date', Carbon::now()->subMonths(1)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $status = $request->input('status');
        $product = $request->input('product'); // Assuming you want to filter by product

        // Query orders based on filters
        $orders = Order::query()
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($product, fn($q) => $q->whereHas('product', fn($pq) => $pq->where('name', 'like', '%' . $product . '%'))) // Assuming product name filter
            ->whereBetween('production_start_date', [$startDate, $endDate])
            ->with('product') // Eager load product for display
            ->get();

        // Calculate KPIs
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('status', 'completed')->count();
        $totalQuantityProduced = $orders->where('status', 'completed')->sum('quantity');

        // On-time delivery rate (example logic)
        $onTimeOrders = $orders->filter(function ($order) {
            return $order->status === 'completed' && $order->actual_end_date <= $order->end_date; // Assumes 'actual_end_date' field
        })->count();
        $onTimeRate = $completedOrders > 0 ? round(($onTimeOrders / $completedOrders) * 100, 2) : 0;

        return view('reports.production_summary', compact(
            'orders', 'startDate', 'endDate', 'status', 'product',
            'totalOrders', 'completedOrders', 'totalQuantityProduced', 'onTimeRate'
        ));
    }

    public function resourceUtilization(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfWeek()->format('Y-m-d'));
        $resourceType = $request->input('resource_type');

    // Add a check to ensure dates are not null if you expect them:
    if (empty($startDate) || empty($endDate)) {
        $startDate = '2024-01-01'; // Default or a date far in the past
        $endDate = date('Y-m-d'); // Today's date
        // abort(400, 'Start date and end date are required for this report.');
    }
   
        // Fetch resources, optionally filtered by type
        $resources = Resource::query()
            ->when($resourceType, fn($q) => $q->where('type', $resourceType))
            ->get();

        $utilizationData = [];

        foreach ($resources as $resource) {
            // Calculate total available hours for the resource within the date range
            $availableHours = 0;
            $currentDate = Carbon::parse($startDate)->startOfDay();
            $endCalcDate = Carbon::parse($endDate)->endOfDay();

            // Simple calculation: assume 8 hours/day for available status
            while ($currentDate->lessThanOrEqualTo($endCalcDate)) {
                // Exclude weekends 
                // if (!$currentDate->isWeekend()) {
                    $availableHours += 8; // Assuming 8 working hours per day
                // }
                $currentDate->addDay();
            }

            // Fetch assignments for this resource within the date range
            $assignments = ResourceAssignment::where('resource_id', $resource->id)
                ->where(function($query) use ($startDate, $endDate) {
                    $query->whereBetween('assigned_start_time', [$startDate, $endDate])
                          ->orWhereBetween('assigned_end_time', [$startDate, $endDate])
                          ->orWhere(function ($q) use ($startDate, $endDate) { // Assignments spanning the period
                              $q->where('assigned_start_time', '<=', $startDate)
                                ->where('assigned_end_time', '>=', $endDate);
                          });
                })
                ->get();

            $assignedHours = 0;
            foreach ($assignments as $assignment) {
                // Calculate actual overlap with the reporting period
                $assignmentStart = Carbon::parse($assignment->assigned_start_time);
                $assignmentEnd = Carbon::parse($assignment->assigned_end_time);

                $overlapStart = max($assignmentStart, Carbon::parse($startDate));
                $overlapEnd = min($assignmentEnd, Carbon::parse($endDate));

                if ($overlapStart->lessThan($overlapEnd)) {
                    $assignedHours += $overlapEnd->diffInHours($overlapStart);
                }
            }

            $utilizationRate = ($availableHours > 0) ? round(($assignedHours / $availableHours) * 100, 2) : 0;

            $utilizationData[] = [
                'id' => $resource->id,
                'name' => $resource->name,
                'type' => $resource->type,
                'status' => $resource->status,
                'available_hours' => $availableHours,
                'assigned_hours' => $assignedHours,
                'utilization_rate' => $utilizationRate,
            ];
        }

        // Logic for resource utilization report
        $resourceUsage = ResourceAssignment::whereBetween('production_start_date', [$startDate, $endDate])->get();
        return view('reports.resource_utilization', compact('utilizationData', 'startDate', 'endDate', 'resourceType'));

    }
}