<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;

class AdminDashboardController extends Controller
{
    protected DashboardService $dashboard;

    public function __construct(DashboardService $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function index()
    {
        $metrics = $this->dashboard->getMetrics();
        $topProductIds = $this->dashboard->getTopProductIds();

        return view('dashboard', [
            'actualSales' => $this->dashboard->getActualSalesPerMonth(),
            'forecastedSales' => $this->dashboard->getForecastedSalesPerMonth(),
            'productSales' => $this->dashboard->getProductSalesPerMonth($topProductIds),
            'productForecasts' => $this->dashboard->getProductForecasts($topProductIds),
            'topProductIds' => $topProductIds,
            'segmentCounts' => $this->dashboard->getSegmentCounts(),
            'weeklyProductionData' => $this->dashboard->getWeeklyProductionData(),
            'recentSuppliers' => $this->dashboard->getRecentSuppliers(),
            'approvedRequests' => $this->dashboard->getApprovedProcurementsThisMonth(),
            'metrics' => $metrics,
        ]);
    }
}
