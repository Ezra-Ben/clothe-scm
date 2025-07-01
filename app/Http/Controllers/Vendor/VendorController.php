<?php
namespace App\Http\Controllers\Vendor;

use App\Http\Requests\VendorFormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VendorService;

class VendorController extends Controller
{
    public function showForm()
    {
        return view('vendor.register');
    }

    public function submitForm(VendorFormRequest $request)
    {    
        $validated = $request->validated();  
 
        $result = app(VendorService::class)->validateAndRegister($validated);
 
        if ($result['success']) {

            $supplier = $result['supplier'];

            return redirect()
                ->route('supplier.profile')
                ->with('vendor_validated', $result['message']);
        }

        return redirect()
            ->back()
            ->withErrors($result['errors'])
            ->withInput();
    }
}
