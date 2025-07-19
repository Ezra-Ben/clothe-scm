<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Batch;
use App\Models\ProductionActivity;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class ProductionCoordinatorController extends Controller
{
    public function dashboard()
    {
        $ordersInProduction = Order::where('status', 'in_production')->count();
        $batchesInProgress = Batch::where('status', 'in_progress')->count();
        $totalRelevantSchedulesCount = Schedule::whereNotIn('status', ['completed', 'cancelled']) // Exclude finished schedules
                                         ->where(function ($query) {
                                             $query->whereNull('end_date') // Schedules with no specific end date
                                                   ->orWhere('end_date', '>=', Carbon::now()); // Schedules ending today or in the future
                                         })
                                         ->count();

        $recentActivities = ProductionActivity::orderBy('timestamp', 'desc') // Order by the 'timestamp' column
                                            ->limit(5) // Get the 5 most recent activities
                                            ->get();
                $bottlenecks = []; // Initialize an empty array to store identified bottlenecks

        // 1. Detect Stalled In-Progress Batches
        // Find batches that are 'in_progress' and their last activity is older than a threshold
        $stalledBatches = Batch::where('status', 'in_progress')
                               ->get(); // Get all in-progress batches

        foreach ($stalledBatches as $batch) {
            $lastActivity = ProductionActivity::where('batch_id', $batch->id)
                                             ->orderBy('timestamp', 'desc')
                                             ->first();

            // this is the "stalled" threshold (e.g., 2 days without new activity)
            $stalledThreshold = Carbon::now()->subDays(2);

            if ($lastActivity) {
                // If the last activity was before the threshold
                if ($lastActivity->timestamp->lessThan($stalledThreshold)) {
                    $bottlenecks[] = [
                        'type' => 'Stalled Batch',
                       'description' => "Batch #{$batch->id} (Order: " . ($batch->order ? $batch->order->id : 'N/A') . ") has no recent activity. Last activity: '{$lastActivity->type}' on {$lastActivity->timestamp->format('Y-m-d H:i')}." ,
                        'severity' => 'High',
                    ];
                }
            } else {
                // If an in-progress batch has no activities at all, and its start date is old
                if ($batch->start_date && $batch->start_date->lessThan($stalledThreshold)) {
                    $bottlenecks[] = [
                        'type' => 'Batch Initiated, No Activities',
                        'description' => "Batch #{$batch->id} (Order: " . ($batch->order ? $batch->order->id : 'N/A') . ") started on {$batch->start_date->format('Y-m-d')}, but has no recorded activities.",
                        'severity' => 'High',
                    ];
                }
            }
        }

        // 2. Detect Backlog in a Specific Activity Type (e.g., Quality Control)
        // If there are 'in_progress' batches, but the oldest 'Quality Control Check' is significantly old,
        // or there's a disproportionate number of 'in_progress' batches to recent QC activities.

        // Threshold for old QC activity (e.g., older than 1 day)
        $qcBacklogThreshold = Carbon::now()->subDay(1);

        $oldestQcActivity = ProductionActivity::where('type', 'Quality Control Check')
                                            ->orderBy('timestamp', 'asc')
                                            ->first();

        if ($oldestQcActivity && $oldestQcActivity->timestamp->lessThan($qcBacklogThreshold)) {
            $bottlenecks[] = [
                'type' => 'Quality Control Backlog',
                'description' => "Oldest Quality Control Check recorded is from {$oldestQcActivity->timestamp->format('Y-m-d H:i')}. This might indicate a bottleneck in the QC department.",
                'severity' => 'Medium',
            ];
        }
                                    
        return view('production.production_dashboard', compact('ordersInProduction', 'batchesInProgress', 'totalRelevantSchedulesCount','recentActivities', 'bottlenecks'));
    }
    public function orders()
    {
        $orders = Order::whereIn('status', ['in_production', 'pending_production', 'completed_production'])
                        ->with(['customer', 'product'])
                        ->paginate(10);
        return view('production.orders', compact('orders'));
    }
    public function showOrder($id)
    {
        $order = Order::with(['customer', 'product', 'batches.activities'])->findOrFail($id);
        return view('production.order_detail', compact('order'));
    }
    public function batches()
    {
        $batches = Batch::with('product')
                        ->paginate(10);
        return view('production.batches', compact('batches'));
    }
    public function showBatch($id)
    {
        $batch = Batch::with(['order', 'product', 'activities'])->findOrFail($id);
        return view('production.batch_detail', compact('batch'));
    }
    
   public function schedules()
    {
        $schedules = Schedule::with('batches')->paginate(10);
        return view('production.schedules', compact('schedules'));
    }
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,active,completed,cancelled',
            'batch_ids' => 'nullable|string',
        ]);
        $schedule = Schedule::create($request->only('description', 'start_date', 'end_date', 'status'));
        if ($request->filled('batch_ids')) {
            $batchIds = array_map('trim', explode(',', $request->batch_ids));
            Batch::whereIn('id', $batchIds)->update(['schedule_id' => $schedule->id]);
        }
        return redirect()->route('production.schedules')->with('success', 'Schedule created successfully!');
    }
    public function updateSchedule(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,active,completed,cancelled',
            'batch_ids' => 'nullable|string',
        ]);
        $schedule = Schedule::findOrFail($id);
        $schedule->update($request->only('description', 'start_date', 'end_date', 'status'));
        if ($request->filled('batch_ids')) {
            $currentBatchIds = $schedule->batches->pluck('id')->toArray();
            $newBatchIds = array_map('trim', explode(',', $request->batch_ids));
            Batch::whereIn('id', array_diff($currentBatchIds, $newBatchIds))->update(['schedule_id' => null]);
            Batch::whereIn('id', array_diff($newBatchIds, $currentBatchIds))->update(['schedule_id' => $schedule->id]);
        } else {
            $schedule->batches()->update(['schedule_id' => null]);
        }
        return redirect()->route('production.schedules')->with('success', 'Schedule updated successfully!');
    }
    public function destroySchedule($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->batches()->update(['schedule_id' => null]);
        $schedule->delete();
        return redirect()->route('production.schedules')->with('success', 'Schedule deleted successfully!');
    }
     public function storeActivity(Request $request, Batch $batch)
    {
        try {
            $validatedData = $request->validate([
                'description' => 'required|string|max:500',
                'type' => 'required|string|max:255',
                'timestamp' => 'required|date', // 'date' validation handles various formats
            ]);

            $activity = $batch->activities()->create([ 
                'description' => $validatedData['description'],
                'type' => $validatedData['type'],
                'timestamp' => Carbon::parse($validatedData['timestamp']), // Convert string to Carbon instance
            ]);

            // Return a success JSON response
            return response()->json([
                'success' => true,
                'message' => 'Activity added successfully!',
                'activity' => $activity
            ], 201); // 201 Created status code

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors for debugging
            Log::error("Validation error adding activity for Batch {$batch->id}: " . json_encode($e->errors()));
            // Return validation errors as JSON
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422); // 422 Unprocessable Entity status code
        } catch (\Exception $e) {
            // Log any other unexpected errors
            Log::error("Server error adding activity for Batch {$batch->id}: " . $e->getMessage());
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'Failed to add activity. An internal error occurred.'
            ], 500); // 500 Internal Server Error status code
        }
    }
    public function updateBatchStatus(Request $request, Batch $batch)
    {
        try {
            // 1. Validation: Ensure the new status is provided and valid.
            $validatedData = $request->validate([
                'status' => 'required|string|in:pending,in_progress,completed,cancelled', // Ensure these match your actual batch statuses
            ]);

            // 2. Update the batch status
            $batch->update([
                'status' => $validatedData['status']
            ]);
            // Return a success JSON response
            return response()->json([
                'success' => true,
                'message' => 'Batch status updated successfully!',
                'batch' => $batch // Return the updated batch object
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors
            Log::error("Validation error updating status for Batch {$batch->id}: " . json_encode($e->errors()));
            // Return validation errors as JSON
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error("Server error updating status for Batch {$batch->id}: " . $e->getMessage());
            // Return a generic error message
            return response()->json([
                'success' => false,
                'message' => 'Failed to update batch status. An internal error occurred.'
            ], 500);
        }
    }
}