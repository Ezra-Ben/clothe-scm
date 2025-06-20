<?php

namespace App\Http\Controllers\Supplier;

use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::with('supplier')->get();
        return view('supplier.contracts.index', compact('contracts'));
    }

    public function show($id)
    {
        $contract = Contract::findOrFail($id);
        return view('supplier.contracts.show', compact('contract'));
    }

    public function create()
    {
        return view('supplier.contracts.create'); // You can create this view if needed
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'file' => 'required|file|mimes:pdf,doc,docx',
            'status' => 'required|in:active,expired',
        ]);

        // Upload file
        $filePath = $request->file('file')->store('contracts', 'public');

        // Save to DB
        Contract::create([
            'supplier_id' => $validated['supplier_id'],
            'file_url' => $filePath,
            'uploaded_by' => auth()->id(), // assumes logged-in admin
            'status' => $validated['status'],
            'uploaded_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Contract uploaded successfully.');
    }
}
