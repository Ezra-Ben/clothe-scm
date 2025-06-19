<?php
// app/Http/Controllers/PerformanceController.php
namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\PerformanceService;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    protected $service;

    public function __construct(PerformanceService $service)
    {
        $this->middleware('can:manage_performance')->except('index');
        $this->service = $service;
    }

    public function index(Supplier $supplier)
    {
        return view('supplier.performance.index', [
            'supplier' => $supplier,
            'performances' => $this->service->getPerformanceHistory($supplier),
            'averageRating' => $this->service->calculateAverageRating($supplier)
        ]);
    }
    

    public function store(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'performance_note' => 'required|string|max:500'
        ]);

        $this->service->recordReview($validated, $supplier, auth()->id());

        return back()->with('success', 'Performance review recorded!');
    }
    
}