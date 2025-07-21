<?php

namespace App\Http\Controllers\Production;

use App\Models\QualityControl;
use App\Models\ProductionBatch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QualityControlController extends Controller
{
   
    public function create()
    {
        return view('quality_control.create');
    }

   
    public function store(Request $request)
    {
        $validated = $request->validate([
            'production_batch_id'     => 'required|exists:production_batches,id',
            'status'                  => 'required|in:passed,failed',
            'inspection_date'         => 'required|date',
            'defect_count'            => 'required|integer|min:0',
            'notes'                   => 'nullable|string',
            'corrective_action_taken' => 'nullable|string|required_if:status,failed',
        ]);

        $qc = QualityControl::create($validated);

        return redirect()
            ->route('quality_control.show', $qc->id)
            ->with('success', 'Quality control record created successfully.');
    }

    public function show(QualityControl $quality_control)
    {
        $qc = $quality_control;
        
        return view('quality_control.show', compact('qc'));
    }
}
