<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Models\Product;

class AdminDashboardController extends Controller
{
    protected DashboardService $dashboard;

    public function __construct(DashboardService $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function index()
    {   $year = now()->year;
        $metrics = $this->dashboard->getMetrics();
        $topProductIds = $this->dashboard->getTopProductIds();
        $topProducts = Product::whereIn('id', $topProductIds)->pluck('name', 'id');

        return view('dashboard', [
            'actualSales' => $this->dashboard->getActualSalesPerMonth($year),
            'forecastedSales' => $this->dashboard->getForecastedSalesPerMonth($year),
            'productSales' => $this->dashboard->getProductSalesPerMonth($topProductIds, $year),
            'productForecasts' => $this->dashboard->getProductForecasts($topProductIds, $year),
            'topProductIds' => $topProductIds,
            'topProducts' => $topProducts,
            'segmentCounts' => $this->dashboard->getSegmentCounts(),
            'weeklyProductionData' => $this->dashboard->getWeeklyProductionData(),
            'recentSuppliers' => $this->dashboard->getRecentSuppliers(),
            'approvedRequests' => $this->dashboard->getApprovedProcurementsThisWeek(),
            'metrics' => $metrics,
        ]);
    }
}
