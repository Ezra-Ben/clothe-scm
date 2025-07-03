<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\ProductionOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    /**
     * Display various production-related reports.
     */
    public function productionReports()
    {
        // Business Logic: Calculate Stock vs MTO %
        $totalOrderItems = OrderItem::count();
        $fulfilledFromStock = OrderItem::where('fulfilled_from_stock', true)->count();
        $fulfilledByMTO = OrderItem::where('fulfilled_from_stock', false)->count();

        $stockPercentage = $totalOrderItems > 0 ? ($fulfilledFromStock / $totalOrderItems) * 100 : 0;
        $mtoPercentage = $totalOrderItems > 0 ? ($fulfilledByMTO / $totalOrderItems) * 100 : 0;

        // Business Logic: Calculate Production Lead Time
        // Lead time = completed_at - created_at
        $completedProductionOrders = ProductionOrder::where('status', 'Completed')
                                                    ->whereNotNull('completed_at')
                                                    ->whereNotNull('created_at')
                                                    ->get();
        $totalLeadTimeMinutes = 0;
        foreach ($completedProductionOrders as $order) {
            $totalLeadTimeMinutes += $order->created_at->diffInMinutes($order->completed_at);
        }
        $averageLeadTimeHours = $completedProductionOrders->count() > 0
                                ? round(($totalLeadTimeMinutes / $completedProductionOrders->count()) / 60, 2)
                                : 0;

        // Business Logic: Calculate Raw Material Usage
        // This is a join query to sum up raw materials used based on completed production orders and their BOMs
        $rawMaterialUsage = DB::table('production_orders')
                            ->join('boms', 'production_orders.bom_id', '=', 'boms.id')
                            ->join('bom_items', 'boms.id', '=', 'bom_items.bom_id')
                            ->join('raw_materials', 'bom_items.raw_material_id', '=', 'raw_materials.id')
                            ->select(
                                'raw_materials.name as raw_material_name',
                                'raw_materials.unit',
                                DB::raw('SUM(bom_items.quantity * production_orders.quantity) as total_used')
                            )
                            ->where('production_orders.status', 'Completed')
                            ->groupBy('raw_materials.name', 'raw_materials.unit')
                            ->get();

        return view('ProductionReports', compact(
            'stockPercentage', 'mtoPercentage',
            'averageLeadTimeHours',
            'rawMaterialUsage'
        ));
    }
}
