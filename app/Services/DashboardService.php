<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory;
use App\Models\ProcurementRequest;
use App\Models\InboundShipment;
use Illuminate\Support\Facades\Storage;

class DashboardService
{
    public function getMetrics()
    {
        $activeCustomers = User::whereHas('orders', fn ($q) =>
            $q->where('created_at', '>=', Carbon::now()->subDays(60))
        )->get()->filter(fn($user) => $user->hasRole('customer'))->count();

        $supplierCount = User::all()->filter(fn($u) => $u->hasRole('supplier'))->count();

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

    public function getActualSalesPerMonth()
    {
        $before = DB::table('historical_sales')
            ->selectRaw('MONTH(sale_date) as month, SUM(total_amount) as total')
            ->whereYear('sale_date', 2025)->groupBy('month');

        $after = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('MONTH(orders.order_date) as month, SUM(order_items.total_price) as total')
            ->whereYear('orders.order_date', 2025)->groupBy('month');

        return $before->unionAll($after)->get();
    }

    public function getForecastedSalesPerMonth()
    {
        return DB::table('forecasts')
            ->selectRaw('MONTH(forecast_month) as month, SUM(predicted_quantity * avg_price) as total')
            ->whereYear('forecast_month', 2025)->groupBy('month')->get();
    }

    public function getProductSalesPerMonth(array $productIds)
    {
        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('product_id, MONTH(orders.order_date) as month, SUM(quantity) as total')
            ->whereIn('product_id', $productIds)
            ->whereYear('orders.order_date', 2025)
            ->groupBy('product_id', 'month')->get();
    }

    public function getProductForecasts(array $productIds)
    {
        return DB::table('forecasts')
            ->selectRaw('product_id, MONTH(forecast_month) as month, SUM(predicted_quantity) as total')
            ->whereIn('product_id', $productIds)
            ->whereYear('forecast_month', 2025)
            ->groupBy('product_id', 'month')->get();
    }

    public function getSegmentCounts()
    {
        return DB::table('customer_segment')
            ->select('cluster', DB::raw('COUNT(*) as count'))
            ->groupBy('cluster')
            ->pluck('count', 'cluster')
            ->toArray();
    }

    public function getWeeklyProductionData()
    {
        $data = DB::table('production_orders')
            ->selectRaw('YEARWEEK(created_at, 1) as week, status, SUM(quantity) as total')
            ->groupBy('week', 'status')->orderBy('week')->get();

        $result = [];
        foreach ($data as $row) {
            $week = $row->week;
            $result[$week] ??= ['completed' => 0, 'pending' => 0, 'failed' => 0];
            $result[$week][$row->status] = $row->total;
        }

        return $result;
    }

    public function getRecentSuppliers()
    {
        return InboundShipment::where('created_at', '>=', now()->subWeek())
            ->with('procurementRequest.supplier.user')
            ->get()
            ->map(fn($s) => $s->procurementRequest->supplier->user->name ?? 'Unknown')
            ->unique()->values();
    }

    public function getApprovedProcurementsThisMonth()
    {
        return ProcurementRequest::where('status', 'accepted')
            ->where('updated_at', '>=', now()->startOfMonth())
            ->get()
            ->map(fn($p) =>
                "Procurement request #{$p->id} approved for {$p->quantity} units"
            );
    }
}
