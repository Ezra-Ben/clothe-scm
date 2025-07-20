<?php
namespace App\Services;
use App\Models\Order;
use App\Models\Batch;
use App\Models\ProductionActivity;
use App\Models\Schedule;
use Carbon\Carbon;
class ProductionService
{
    public function getDashboardCounts()
    {
        return [
            'orders_in_production' => Order::where('status', 'in_production')->count(),
            'batches_in_progress' => Batch::where('status', 'in_progress')->count(),
            'total_relevant_schedules' => Schedule::whereNotIn('status', ['completed', 'cancelled']) // This is the new logic
                                                   ->where(function ($query) {
                                                       $query->whereNull('end_date')
                                                             ->orWhere('end_date', '>=', Carbon::now());
                                                   })
                                                   ->count(),
        ];
    }
    public function getRecentProductionActivities($limit = 5)
    {
        return ProductionActivity::latest()->limit($limit)->get();
    }
    public function updateBatchStatus($batchId, $newStatus)
    {
        $batch = Batch::findOrFail($batchId);
        $batch->status = $newStatus;
        if ($newStatus === 'completed') {
            $batch->end_date = now(); // Set end date if batch is completed
        }
        $batch->save();
        return $batch;
    }
    public function addBatchActivity($batchId, $description, $type = null, $timestamp = null)
    {
        $batch = Batch::findOrFail($batchId);
        $activity = $batch->activities()->create([
            'description' => $description,
            'type' => $type,
            'timestamp' => $timestamp ?? now(),
        ]);
        return $activity;
    }
}
