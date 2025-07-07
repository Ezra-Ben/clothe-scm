<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductionBatch;
use App\Models\QualityControl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the main analytics dashboard
     */
    public function index()
    {
        // Basic statistics
        $stats = [
            'total_products' => Product::count(),
            'total_batches' => ProductionBatch::count(),
            'total_qc_records' => QualityControl::count(),
            'low_stock_products' => Product::where('stock', '<', 10)->count(),
            'completed_batches' => ProductionBatch::where('status', 'completed')->count(),
            'passed_qc' => QualityControl::where('status', 'passed')->count(),
        ];

        // Monthly trends
        $monthlyData = $this->getMonthlyTrends();
        
        // Stock levels by category
        $stockLevels = $this->getStockLevels();
        
        // Quality control statistics
        $qcStats = $this->getQCStatistics();
        
        // Production efficiency
        $productionEfficiency = $this->getProductionEfficiency();

        return view('reports.index', compact('stats', 'monthlyData', 'stockLevels', 'qcStats', 'productionEfficiency'));
    }

    /**
     * Get monthly trends data
     */
    private function getMonthlyTrends()
    {
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months->push([
                'month' => $date->format('M Y'),
                'products' => Product::whereMonth('created_at', $date->month)
                                   ->whereYear('created_at', $date->year)->count(),
                'batches' => ProductionBatch::whereMonth('created_at', $date->month)
                                          ->whereYear('created_at', $date->year)->count(),
                'qc_records' => QualityControl::whereMonth('created_at', $date->month)
                                            ->whereYear('created_at', $date->year)->count(),
            ]);
        }
        
        return $months;
    }

    /**
     * Get stock levels by category
     */
    private function getStockLevels()
    {
        return [
            'in_stock' => Product::where('stock', '>', 10)->count(),
            'low_stock' => Product::whereBetween('stock', [1, 10])->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
        ];
    }

    /**
     * Get quality control statistics
     */
    private function getQCStatistics()
    {
        return [
            'passed' => QualityControl::where('status', 'passed')->count(),
            'failed' => QualityControl::where('status', 'failed')->count(),
            'pending' => QualityControl::where('status', 'pending')->count(),
            'in_progress' => QualityControl::where('status', 'in_progress')->count(),
        ];
    }

    /**
     * Get production efficiency metrics
     */
    private function getProductionEfficiency()
    {
        $totalBatches = ProductionBatch::count();
        $completedBatches = ProductionBatch::where('status', 'completed')->count();
        
        return [
            'completion_rate' => $totalBatches > 0 ? round(($completedBatches / $totalBatches) * 100, 2) : 0,
            'total_batches' => $totalBatches,
            'completed_batches' => $completedBatches,
            'pending_batches' => ProductionBatch::where('status', 'pending')->count(),
            'in_progress_batches' => ProductionBatch::where('status', 'in_progress')->count(),
        ];
    }

    /**
     * Generate product performance report
     */
    public function productPerformance()
    {
        $products = Product::withCount(['productionBatches', 'qualityControls'])
                          ->withSum('productionBatches', 'quantity')
                          ->orderBy('production_batches_count', 'desc')
                          ->paginate(15);

        return view('reports.product-performance', compact('products'));
    }

    /**
     * Generate quality control report
     */
    public function qualityReport()
    {
        $qcData = QualityControl::with(['productionBatch.product', 'tester'])
                               ->select('status', DB::raw('count(*) as count'))
                               ->groupBy('status')
                               ->get();

        $monthlyQC = QualityControl::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('status'),
            DB::raw('count(*) as count')
        )
        ->whereYear('created_at', now()->year)
        ->groupBy('month', 'year', 'status')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        return view('reports.quality-report', compact('qcData', 'monthlyQC'));
    }

    /**
     * Generate production efficiency report
     */
    public function productionEfficiency()
    {
        $efficiencyData = ProductionBatch::with('product')
                                       ->select(
                                           'status',
                                           DB::raw('count(*) as count'),
                                           DB::raw('avg(quantity) as avg_quantity')
                                       )
                                       ->groupBy('status')
                                       ->get();

        $monthlyProduction = ProductionBatch::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('status'),
            DB::raw('count(*) as count'),
            DB::raw('sum(quantity) as total_quantity')
        )
        ->whereYear('created_at', now()->year)
        ->groupBy('month', 'year', 'status')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

        return view('reports.production-efficiency', compact('efficiencyData', 'monthlyProduction'));
    }

    /**
     * Export report data
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'products');
        $format = $request->get('format', 'csv');

        switch ($type) {
            case 'products':
                $data = Product::all();
                $filename = 'products_report_' . now()->format('Y-m-d') . '.' . $format;
                break;
            case 'batches':
                $data = ProductionBatch::with('product')->get();
                $filename = 'production_batches_report_' . now()->format('Y-m-d') . '.' . $format;
                break;
            case 'qc':
                $data = QualityControl::with(['productionBatch', 'tester'])->get();
                $filename = 'quality_control_report_' . now()->format('Y-m-d') . '.' . $format;
                break;
            default:
                abort(404);
        }

        return $this->generateExport($data, $filename, $format);
    }

    /**
     * Generate export file
     */
    private function generateExport($data, $filename, $format)
    {
        // For now, return a simple CSV export
        // In a real application, you'd use a library like Laravel Excel
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add headers based on data type
            if ($data->first() instanceof Product) {
                fputcsv($file, ['ID', 'Name', 'SKU', 'Price', 'Stock', 'Created At']);
                foreach ($data as $item) {
                    fputcsv($file, [
                        $item->id,
                        $item->name,
                        $item->sku,
                        $item->price,
                        $item->stock,
                        $item->created_at
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 