<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VendorService;
use App\Http\Requests\VendorFormRequest;

class VendorController extends Controller
{

    public function showForm(){
        return view('vendor.register');
    }
    
    public function submitForm(VendorFormRequest $request)
    {
    $validated = $request->validated();

    $result = app(VendorService::class)->validateAndRegister($validated);

    return $result['success']
        ? view('vendor.success')->with('message', $result['message'])
        : redirect()->back()->withErrors($result['errors'])->withInput();
    }

    
}

