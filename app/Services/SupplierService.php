<?php
namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
   public static function getDashboardData($supplierId)
{
    $supplier = Supplier::with(['vendor', 'contracts', 'performanceRecords'])->findOrFail($supplierId);
    
    $supplier->totalContracts = $supplier->contracts->count();
    $supplier->averageRating = $supplier->performanceRecords->avg('rating');

    return $supplier;
}
    
}
