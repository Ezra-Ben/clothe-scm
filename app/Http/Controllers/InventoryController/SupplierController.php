<?php
namespace App\Http\Controllers\InventoryController;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
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
}