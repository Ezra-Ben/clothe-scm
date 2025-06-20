<?php
namespace App\Http\Controllers\Supplier;

use App\Models\Supplier;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function profile($id)
    {
        $supplier = Supplier::with('vendor')->findOrFail($id);
        return view('supplier.profile', compact('supplier'));
    }

    public function dashboard($id)
    {
       $supplier = SupplierService::getDashboardData($id);
    return view('supplier.dashboard', compact('supplier'));
       
    }

    public function update(Request $request, $id)
{
    $supplier = Supplier::findOrFail($id);
    $supplier->update($request->only('address'));
    return redirect()->route('supplier.dashboard', $id)->with('success', 'Address updated!');
}

}