<?php

namespace App\Http\Controllers\Logistics;

use App\Models\Carrier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CarrierController extends Controller
{
    public function index()
    {
        $carriers = Carrier::with('user')->get();
        return view('logistics.carriers.index', compact('carriers'));
    }

    public function show(Carrier $carrier)
    {
        return view('logistics.carriers.show', compact('carrier'));
    }

    public function create()
{
    return view('logistics.carriers.create');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'status' => 'required|in:free,busy',
        'contact_phone' => 'nullable|string|max:255',
        'vehicle_type' => 'required|string|max:255',
        'license_plate' => 'nullable|string|max:255',
        'service_areas' => 'required|string|max:255',
        'max_weight_kg' => 'nullable|integer|min:0',
        'customer_rating' => 'nullable|numeric|min:0|max:10',
    ]);

    Carrier::create($validated);
    return redirect()->route('carriers.index')->with('success', 'Carrier created!');
}

public function edit(Carrier $carrier)
{
    return view('logistics.carriers.edit', compact('carrier'));
}

public function update(Request $request, Carrier $carrier)
{
    $validated = $request->validate([
        'status' => 'required|in:free,busy',
        'contact_phone' => 'nullable|string|max:255',
        'vehicle_type' => 'required|string|max:255',
        'license_plate' => 'nullable|string|max:255',
        'service_areas' => 'required|string|max:255',
        'max_weight_kg' => 'nullable|integer|min:0',
        'customer_rating' => 'nullable|numeric|min:0|max:10',
    ]);

    $carrier->update($validated);
    return redirect()->route('carriers.index')->with('success', 'Carrier updated!');
}

public function destroy(Carrier $carrier)
{
    if ($carrier->outboundShipments()->exists()) {
        return redirect()->back()->with('error', 'Cannot delete carrier with assigned shipments.');
    }

    $carrier->delete();

    return redirect()->route('carriers.index')->with('success', 'Carrier deleted successfully.');
}


}
