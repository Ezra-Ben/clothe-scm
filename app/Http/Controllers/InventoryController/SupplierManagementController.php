<?php
namespace App\Http\Controllers\InventoryController;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierManagementController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('InventoryProcurement.Supplier_management', compact('suppliers'));
    }

    public function activate($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->is_active = true;
        $supplier->save();
        return redirect()->back()->with('success', 'Supplier activated.');
    }

    public function deactivate($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->is_active = false;
        $supplier->save();
        return redirect()->back()->with('success', 'Supplier deactivated.');
    }

    public function showProcurementForm($id)
    {
    $request = \App\Models\ProcurementRequest::findOrFail($id);
    return view('Supplier.procurement_form', compact('request'));
    }

public function deliverProcurement(Request $request, $id)
    {
    $request->validate([
        'delivered_quantity' => 'required|integer|min:1',
    ]);
    $procRequest = \App\Models\ProcurementRequest::findOrFail($id);
    $procRequest->status = 'delivery_accepted';
    $procRequest->save();

    // Notify admin
    foreach (\App\Models\User::where('role', 'admin')->get() as $admin) {
        $admin->notify(new \App\Notifications\SupplierDeliveryAccepted($procRequest));
    }

    return redirect()->route('supplier.dashboard')->with('success', 'Delivery confirmed! Awaiting admin confirmation.');
    }

}