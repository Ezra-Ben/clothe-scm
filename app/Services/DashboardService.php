<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Inventory;
use App\Models\InboundShipment;
use App\Models\ProcurementRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardService
{
    public function getMetrics()
    {
        $activeCustomers = User::whereHas('customer.orders', fn ($q) =>
            $q->where('created_at', '>=', Carbon::now()->subDays(60))
        )->get()->filter(fn($user) => $user->hasRole('customer'))->count();

        $supplierCount = User::whereHas('role', function ($q) {
            $q->where('name', 'supplier');
        })->count();


        return [
            'active_customers' => $activeCustomers,
            'registered_suppliers' => $supplierCount,
            'pending_procurements' => ProcurementRequest::where('status', 'pending')->count(),
            'orders_this_month' => Order::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)->count(),
            'inventory_value' => Inventory::join('products', 'inventories.product_id', '=', 'products.id')
                ->selectRaw('SUM(inventories.quantity_on_hand * products.price) as total')->value('total'),
            'low_stock_items' => Inventory::where('quantity_on_hand', '<', 10)->count(),
        ];
    }

    public function getTopProductIds()
    {
        $json = Storage::get('segment_to_products.json');
        return json_decode($json, true)['1'] ?? [];
    }

    public function getActualSalesPerMonth(int $year)
    {
        $historical = DB::table('historical_sales')
            ->selectRaw('MONTH(date) as month, SUM(quantity) as total')
            ->whereYear('date', $year)
            ->groupBy('month');

        $recent = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('MONTH(orders.created_at) as month, SUM(order_items.quantity) as total')
            ->where('orders.status', 'paid')
            ->whereYear('orders.created_at', $year)
            ->groupBy('month');
        

        return DB::table(function ($query) use ($historical, $recent) {
            $query->fromSub($historical->unionAll($recent), 'monthly_sales');
        })
            ->selectRaw('month, SUM(total) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function getForecastedSalesPerMonth(int $year)
    {
        return DB::table('forecasts')
            ->selectRaw('MONTH(forecasts.forecast_month) as month, SUM(forecasts.predicted_quantity) as total')
            ->whereYear('forecasts.forecast_month', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    public function getProductSalesPerMonth(array $productIds, int $year)
    {
        $historical = DB::table('historical_sales')
            ->selectRaw('product_id, MONTH(date) as month, SUM(quantity) as total')
            ->whereYear('date', $year)
            ->whereIn('product_id', $productIds)
            ->groupBy('product_id', 'month');

        $recent = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('order_items.product_id, MONTH(orders.created_at) as month, SUM(order_items.quantity) as total')
            ->whereYear('orders.created_at', $year)
            ->where('orders.status', 'paid')
            ->whereIn('order_items.product_id', $productIds)
            ->groupBy('order_items.product_id', 'month');

        return DB::table(function ($query) use ($historical, $recent) {
            $query->fromSub($historical->unionAll($recent), 'product_sales');
        })
        ->selectRaw('product_id, month, SUM(total) as total')
        ->groupBy('product_id', 'month')
        ->orderBy('product_id')
        ->orderBy('month')
        ->get();
    }

    public function getProductForecasts(array $productIds, int $year)
    {
        return DB::table('forecasts')
            ->join('products', 'forecasts.product_id', '=', 'products.id')
            ->selectRaw('product_id, MONTH(forecast_month) as month, SUM(predicted_quantity) as total')
            ->whereIn('product_id', $productIds)
            ->whereYear('forecast_month', $year)
            ->groupBy('product_id', 'month')
            ->orderBy('product_id')
            ->orderBy('month')
            ->get();
    }

    public function getSegmentCounts()
    {
        return DB::table('customer_segments')
            ->select('segment_id', DB::raw('COUNT(*) as count'))
            ->groupBy('segment_id')
            ->pluck('count', 'segment_id')
            ->toArray();
    }

    public function getWeeklyProductionData()
    {
        $data = DB::table('production_orders')
            ->selectRaw('YEARWEEK(created_at, 1) as week, status, SUM(quantity) as total')
            ->groupBy('week', 'status')
            ->orderBy('week')
            ->get();

        $result = [];
        foreach ($data as $row) {
            $week = $row->week;
            $status = $row->status;

        $result[$week] ??= [];
        $result[$week][$status] = (int) $row->total;
    }

    return $result;
    }

    public function getRecentSuppliers()
    {
        return InboundShipment::where('created_at', '>=', now()->subWeek())
            ->with('procurementRequest.supplier.vendor')
            ->where('status','delivered')
            ->get()
            ->map(function($shipment) {
                return optional($shipment->procurementRequest?->supplier?->vendor)->name ?? 'Unknown';
            })
            ->filter(fn($name) => $name !== 'Unknown')
            ->countBy()
            ->sortDesc()
            ->toArray();
    }

    public function getApprovedProcurementsThisWeek()
    {
        return ProcurementRequest::with('rawMaterial', 'supplier.vendor')
            ->where('status', 'accepted')
            ->where('updated_at', '>=', now()->subDays(7))
            ->orderByDesc('updated_at')
            ->get()
            ->map(function ($p) {
                $material = optional($p->rawMaterial)->name ?? 'Unknown Material';
                $vendor = optional($p->supplier?->vendor)->name ?? 'Unknown Vendor';
                $date = $p->updated_at->format('Y-m-d');

                return "Procurement request #{$p->id} for {$p->quantity} units of {$material} from {$vendor} approved on {$date}";
        });
    }
}
