<?php

namespace App\Http\Controllers\Supplier;

use App\Models\Supplier;
use App\Services\PerformanceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PerformanceController extends Controller
{
    protected $service;

    public function __construct(PerformanceService $service)
    {
        $this->service = $service;
    }

   

    public function index($id = null)
    {
        $supplier = $id
            ? Supplier::with(['vendor', 'performances.createdBy'])->findOrFail($id)
            : auth()->user()->vendor->supplier()->with(['performances.createdBy'])->first();

        return view('supplier.performance.index', [
            'supplier' => $supplier,
            'performances' => $this->service->getPerformanceHistory($supplier),
        ]);
    }



    public function store(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'performance_note' => 'required|string|max:500',
        ]);

        $this->service->recordReview($validated, $supplier, auth()->id());

        return back()->with('success', 'Performance review recorded!');
    }
}
