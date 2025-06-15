<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use illuminate\Support\Facades\Storage;
use App\Services\VendorService;
class VendorRegisterController extends Controller
{
    public function submit(StoreVendorRequest $request)
    {
    $data = $request->validated();

    $result = vendorService::handleRegistration($data);

    if ($result['status'] === 'approved'){
        return redirect ()->back()->with('success', 'Vendor registered and validated successfully!');
        } else {
            return redirect()->back()->withErrors(['validation' => $result['message'] ?? 'Validation failed.']);
        }
    }

    
    }

