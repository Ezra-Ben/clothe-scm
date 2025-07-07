<?php

namespace App\Http\Controllers;

use App\Http\Requests\QualityControlRequest;
use App\Models\QualityControl;
use App\Models\ProductionBatch;
use App\Models\User;

class QualityControlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = QualityControl::with(['productionBatch', 'tester']);

        // Search functionality
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('productionBatch', function($batchQuery) use ($search) {
                    $batchQuery->where('batch_number', 'like', "%{$search}%");
                })
                ->orWhereHas('tester', function($testerQuery) use ($search) {
                    $testerQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Status filter
        if (request('status_filter')) {
            $query->where('status', request('status_filter'));
        }

        // Date filter
        if (request('date_filter')) {
            switch (request('date_filter')) {
                case 'today':
                    $query->whereDate('tested_at', today());
                    break;
                case 'week':
                    $query->whereBetween('tested_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereBetween('tested_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    break;
            }
        }

        $qualityControls = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('quality_controls.index', compact('qualityControls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productionBatches = ProductionBatch::all();
        $testers = User::all();
        return view('quality_controls.create', compact('productionBatches', 'testers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QualityControlRequest $request)
    {
        QualityControl::create($request->validated());
        return redirect()->route('quality-controls.index')->with('success', 'Quality control record created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(QualityControl $qualityControl)
    {
        $qualityControl->load(['productionBatch', 'tester']);
        return view('quality_controls.show', compact('qualityControl'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QualityControl $qualityControl)
    {
        $productionBatches = ProductionBatch::all();
        $testers = User::all();
        return view('quality_controls.edit', compact('qualityControl', 'productionBatches', 'testers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(QualityControlRequest $request, QualityControl $qualityControl)
    {
        $qualityControl->update($request->validated());
        return redirect()->route('quality-controls.index')->with('success', 'Quality control record updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QualityControl $qualityControl)
    {
        $qualityControl->delete();
        return redirect()->route('quality-controls.index')->with('success', 'Quality control record deleted successfully!');
    }
}
