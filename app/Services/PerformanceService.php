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
 
    
    public function getPerformanceHistory(Supplier $supplier)
    {
        return $supplier->performances()
        ->with('createdBy')
        ->latest()
        ->paginate(10);
    }
 }