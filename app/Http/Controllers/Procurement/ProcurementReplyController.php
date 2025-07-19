<?php

namespace App\Http\Controllers\Procurement;

use App\Models\ProcurementReply;
use App\Models\ProcurementRequest;
use App\Models\User;
use App\Notifications\NewProcurementReplyNotification;
use App\Notifications\MaterialDeliveryNotification;
use App\Notifications\DeliveryRejectedNotification;
use App\Notifications\DeliveryAcceptedNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProcurementReplyController extends Controller
{
    public function index()
    {
        $supplierId = auth()->user()->vendor?->supplier?->id;
        $replies = ProcurementReply::with('request')
            ->where('supplier_id', $supplierId)
            ->latest()
            ->get();

        return view('procurement.replies.index_supplier', compact('replies'));
    }

    public function indexForRequest($procurementRequest)
    {
        // Accept both model binding and ID
        if (is_numeric($procurementRequest)) {
            $procurementRequest = ProcurementRequest::with('supplier')->findOrFail($procurementRequest);
        }
        
        $replies = ProcurementReply::where('procurement_request_id', $procurementRequest->id)->get();

        return view('procurement.replies.index_for_request', compact('procurementRequest', 'replies'));
    }

    public function create($requestId)
    {
        $procurementRequest = ProcurementRequest::findOrFail($requestId);
        return view('procurement.replies.create', compact('procurementRequest'));
    }

    public function store(Request $httpRequest, $requestId)
    {
        $validated = $httpRequest->validate([
            'quantity_confirmed' => 'required|integer|min:1',
            'expected_delivery_date' => 'required|date',
            'status' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $reply = ProcurementReply::create([
            'procurement_request_id' => $requestId,
            'supplier_id' => auth()->user()->vendor?->supplier?->id,
            'quantity_confirmed' => $validated['quantity_confirmed'],
            'expected_delivery_date' => $validated['expected_delivery_date'],
            'status' => $validated['status'],
            'remarks' => $validated['remarks'],
        ]);

        // Load relationships for notification
        $reply->load('supplier.vendor.user');

        // Send notifications to admin and procurement managers
        $notifiableUsers = User::whereHas('role', function($query) {
            $query->whereIn('name', ['admin', 'procurement_manager']);
        })->get();

        foreach ($notifiableUsers as $user) {
            $user->notify(new NewProcurementReplyNotification($reply));
        }

        return redirect()->route('procurement.replies.index')->with('success', 'Reply submitted successfully!');
    }

    public function show($replyId)
    {
        $reply = ProcurementReply::with(['request', 'supplier.vendor.user'])->findOrFail($replyId);
        return view('procurement.replies.show', compact('reply'));
    }

    public function edit($replyId)
    {
        $reply = ProcurementReply::where('supplier_id', auth()->user()->vendor?->supplier?->id)
            ->findOrFail($replyId);

        return view('procurement.replies.edit', compact('reply'));
    }

    public function update(Request $httpRequest, $replyId)
    {
        $validated = $httpRequest->validate([
            'quantity_confirmed' => 'required|integer|min:1',
            'expected_delivery_date' => 'required|date',
            'status' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $reply = ProcurementReply::where('supplier_id', auth()->user()->vendor?->supplier?->id)
            ->findOrFail($replyId);

        $reply->update($validated);

        return redirect()->route('procurement.replies.index')->with('success', 'Reply updated.');
    }

    public function destroy($replyId)
    {
        $reply = ProcurementReply::where('supplier_id', auth()->user()->vendor?->supplier?->id)
            ->findOrFail($replyId);

        $reply->delete();

        return redirect()->route('procurement.replies.index')->with('success', 'Reply deleted.');
    }


    public function markDelivered(ProcurementReply $reply)
    {
        // Supplier marks materials as delivered (no inventory update yet)
        $reply->update(['status' => 'delivered']);

        // Notify admin/procurement managers of delivery
        $notifiableUsers = \App\Models\User::whereHas('role', function($query) {
            $query->whereIn('name', ['admin', 'procurement_manager']);
        })->get();

        foreach ($notifiableUsers as $user) {
            $user->notify(new \App\Notifications\MaterialDeliveryNotification($reply));
        }

        return back()->with('success', 'Materials marked as delivered. Admin/Procurement Manager will be notified for inspection.');
    }

    public function acceptDelivery(ProcurementReply $reply)
    {
        // Admin/Procurement Manager accepts the delivery after inspection
        $reply->update(['status' => 'delivered_accepted']);

        $rawMaterial = $reply->request->rawMaterial;
        $rawMaterial->quantity_on_hand += $reply->quantity_confirmed;
        $rawMaterial->save();

        // Try to start production after successful delivery
        $productionService = new \App\Services\ProductionService(new \App\Services\InventoryService());
        $productionService->tryStartPlannedProduction($rawMaterial->id);

        // Notify supplier of acceptance
        $supplier = $reply->supplier;
        if ($supplier && $supplier->vendor && $supplier->vendor->user) {
            $supplier->vendor->user->notify(new \App\Notifications\DeliveryAcceptedNotification($reply));
        }

        return back()->with('success', 'Delivery accepted! Materials added to inventory and supplier notified. Production planning updated.');
    }

    public function rejectDelivery(Request $request, ProcurementReply $reply)
    {
        // Admin rejects the delivery due to quality/quantity issues
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $reply->update([
            'status' => 'delivered_rejected',
            'rejection_reason' => $validated['rejection_reason']
        ]);

        // Notify supplier of rejection
        $supplier = $reply->supplier;
        if ($supplier && $supplier->vendor && $supplier->vendor->user) {
            $supplier->vendor->user->notify(new \App\Notifications\DeliveryRejectedNotification($reply));
        }

        return back()->with('error', 'Delivery rejected. Supplier has been notified with the reason.');
    }
}
