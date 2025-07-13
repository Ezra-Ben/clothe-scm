<?php

namespace App\Http\Controllers\Procurement;

use App\Models\ProcurementRequest;
use App\Models\Supplier;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewProcurementRequestNotification;

class ProcurementRequestController extends Controller
{
    /**
     * Display a listing of the procurement requests.
     * - Admin sees all.
     * - Supplier sees only their assigned requests.
     */
    public function index()
    {
        if (auth()->user()->can('supplier')) {
            $requests = ProcurementRequest::with(['supplier', 'rawMaterial'])
                ->where('supplier_id', auth()->user()->vendor->supplier->id)
                ->latest()
                ->get();
        } else {
            $requests = ProcurementRequest::with(['supplier', 'rawMaterial'])
                ->latest()
                ->get();
        }

        return view('procurement.requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new procurement request.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $raw_materials = RawMaterial::all();

        return view('procurement.requests.create', compact('suppliers', 'raw_materials'));
    }

    /**
     * Store a new procurement request.
     */
    public function store(Request $httpRequest)
    {
        $validated = $httpRequest->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'raw_material_id' => 'required|exists:raw_materials,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $procurementRequest = ProcurementRequest::create([
            'supplier_id' => $validated['supplier_id'],
            'raw_material_id' => $validated['raw_material_id'],
            'quantity' => $validated['quantity'],
            'status' => 'pending',
        ]);

        // Send notification to supplier
        $procurementRequest->load('supplier.vendor.user');
        $supplier = $procurementRequest->supplier?->vendor?->user;

        if ($supplier) {
            $supplier->notify(new NewProcurementRequestNotification($procurementRequest));
        }

        return redirect()->route('procurement.requests.index')->with('success', 'Procurement request created successfully.');
    }

    /**
     * Display the specified procurement request.
     */
    public function show(ProcurementRequest $procurementRequest)
    {   
        if (auth()->user()->can('supplier') && request()->user()->unreadNotifications()->count()) {
            request()->user()->unreadNotifications
                ->where('data.request_id', $procurementRequest->id)
                ->markAsRead();
        }

        return view('procurement.requests.show', compact('procurementRequest'));
    }

    /**
     * Show the form for editing the procurement request.
     */
    public function edit(ProcurementRequest $procurementRequest)
    {
        $suppliers = Supplier::all();
        $raw_materials = RawMaterial::all();

        return view('procurement.requests.edit', compact('procurementRequest', 'suppliers', 'raw_materials'));
    }

    /**
     * Update the specified procurement request.
     */
    public function update(Request $httpRequest, ProcurementRequest $procurementRequest)
    {
        $validated = $httpRequest->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'raw_material_id' => 'required|exists:raw_materials,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $procurementRequest->update($validated);

        return redirect()->route('procurement.requests.index')->with('success', 'Procurement request updated successfully.');
    }

    /**
     * Remove the specified procurement request.
     */
    public function destroy(ProcurementRequest $procurementRequest)
    {
        $procurementRequest->delete();

        return redirect()->route('procurement.requests.index')->with('success', 'Procurement request deleted successfully.');
    }

    /**
     * Supplier accepts the request.
     */
    public function accept(ProcurementRequest $procurementRequest)
    {
        $procurementRequest->update(['status' => 'accepted']);

        return redirect()->route('procurement.requests.show', $procurementRequest->id)->with('success', 'Procurement request accepted successfully.');
    }

    /**
     * Supplier rejects the request.
     */
    public function reject(Request $httpRequest, ProcurementRequest $procurementRequest)
    {
        $validated = $httpRequest->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        // Update request status to rejected
        $procurementRequest->update(['status' => 'rejected']);

        // Create a reply with rejection details
        $procurementRequest->replies()->create([
            'supplier_id' => auth()->user()->vendor->supplier->id,
            'quantity_confirmed' => 0, 
            'expected_delivery_date' => null, 
            'status' => 'rejected',
            'remarks' => $validated['rejection_reason'],
        ]);

        return redirect()->route('procurement.requests.show', $procurementRequest->id)
            ->with('success', 'Request rejected successfully.');
    }
}
