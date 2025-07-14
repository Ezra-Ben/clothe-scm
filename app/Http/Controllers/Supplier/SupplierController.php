<?php
namespace App\Http\Controllers\Supplier;

use App\Models\Supplier;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{   
    public function profile($id = null) {

    $supplier = $id 
            ? Supplier::with(['vendor', 'addedBy'])->findOrFail($id)
            : auth()->user()->vendor->supplier()->with(['vendor', 'addedBy'])->first();         

    return view('supplier.profile', compact('supplier'));
    }
    
    public function dashboard($id = null)
    {
    $supplier = $id
            ? Supplier::with(['vendor', 'addedBy', 'contracts.addedBy', 'performances.createdBy'])->findOrFail($id)
            : auth()->user()->vendor->supplier()->with(['vendor', 'addedBy', 'contracts.addedBy', 'performances.createdBy'])->first();
    $users = app(\App\Http\Controllers\Chat\ChatController::class)->index()->getData()['users'];
    return view('supplier.dashboard', compact('supplier','users'));
    }

    public function update(Request $request)
    {
       $request->validate([
        'address' => 'required|string|max:500',
    ]);

       $supplier = auth()->user()->vendor->supplier;
       $supplier->address = $request->input('address');
       $supplier->save();

       return redirect()
           ->route('supplier.profile')
           ->with('address_updated', 'Supplier Address Updated Successfully!');
    }

}