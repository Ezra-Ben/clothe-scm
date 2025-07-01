<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;

class AdminSupplierController extends Controller
{
    /**
     * Display the supplier selection form for managing contracts.
     */
    public function select()
    {

        // Eager load the 'vendor' relationship to display business names
        $suppliers = Supplier::with('vendor')->get();

        return view('admin.manage-supplier', compact('suppliers'));
    }
}