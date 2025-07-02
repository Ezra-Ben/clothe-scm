<?php

namespace App\Http\Controllers\InventoryController;

use App\Http\Controllers\Controller;
use App\Models\ProcurementRequest;

class SupplierController extends Controller
{
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