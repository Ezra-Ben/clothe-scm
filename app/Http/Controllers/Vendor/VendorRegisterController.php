<?php

namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\VendorService;
use App\Http\Requests\StoreVendorRequest;
class VendorRegisterController extends Controller
{

    public function showForm(){
        return view('vendor.register');
    }
    public function submit(StoreVendorRequest $request)
    {
    $data = $request->validated();

    $result = VendorService::handleRegistration($data);

    if ($result['status'] === 'approved'){
        return redirect ()->back()->with('success', 'Vendor registered and validated successfully!');
        } else {
            return redirect()->back()->withErrors(['validation' => $result['message'] ?? 'Validation failed.']);
        }
    }

    
    }

