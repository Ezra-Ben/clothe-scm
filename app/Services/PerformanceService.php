<?php 

namespace App\Services;

 use App\Models\Supplier;
 use App\Models\Performance ;

 class PerformanceService
 {
    public function recordReview(array $data, Supplier $supplier, int $creatorId):Performance
    {
        return $supplier->performances()->create([
            'performance_note' => $data['performance_note'],
            'rating'=> $data['rating'],
            'created_by' => $creatorId
        ]);
    }
    
    public function calculateAverageRating(Supplier $supplier): float
        {
            return round($supplier->performances()->avg('rating'), 1);
    }
    
    public function getPerformanceHistory(Supplier $supplier)
    {
        return $supplier->performances()
        ->with('creator')
        ->latest()
        ->paginate(10);
    }
 }