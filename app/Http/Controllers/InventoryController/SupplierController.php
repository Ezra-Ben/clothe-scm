<?php

namespace App\Http\Controllers\InventoryController;

use App\Http\Controllers\Controller;
use App\Models\ProcurementRequest;
use Illuminate\Support\Facades\Auth;


class SupplierController extends Controller

{
    public function dashboard()
{
    $user = auth()->user();
    $supplier = $user->supplier; // Adjust if your relation is different

    if ($supplier) {
        $supplier->load([
            'vendor',
            'contracts.addedBy',
            'performances.createdBy'
        ]);
    }

    $notifications = $user->notifications()->latest()->take(10)->get();
    $unread = $user->unreadNotifications->count();

    return view('Supplier.dashboard', compact('supplier', 'notifications', 'unread'));
}
    public function acceptProcurement($id)
    {
        $request = ProcurementRequest::findOrFail($id);
        if ($request->status !== 'approved') {
            return redirect()->route('supplier.dashboard')->with('error', 'Request not available for acceptance.');
        }
        $request->status = 'accepted';
        $request->save();
        return redirect()->route('supplier.procurement.form', $id);
    }

    public function cancelProcurement($id)
    {
        $request = ProcurementRequest::findOrFail($id);
        if ($request->status === 'approved') {
            $request->status = 'cancelled';
            $request->save();
        }
        return redirect()->route('supplier.dashboard')->with('success', 'Procurement request cancelled.');
    }
    
}