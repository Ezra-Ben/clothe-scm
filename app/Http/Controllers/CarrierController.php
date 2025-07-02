<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use App\Services\CarrierService;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateCarrierRequest;

class CarrierController extends Controller
{
     public function __construct(CarrierService $carrierService)
    {
        $this->carrierService = $carrierService;
    }
    

    public function create()
    {
        return view('distributionandlogistics.carriers.create');
    }
    public function destroy(Carrier $carrier)
    {
        $deleted = $this->carrierService->deleteCarrier($carrier);

        if ($deleted) {
            return redirect()->route('distributionandlogistics.carriers.index')
                ->with('success', 'Carrier deleted successfully.');
        } else {
            return redirect()->route('distributionandlogistics.carriers.index')
                ->with('error', 'Failed to delete carrier.');
        }
    }

    public function adminCreate()
    {
        return view('distributionandlogistics.carriers.admin-create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:carriers',
            'contact_phone' => 'required|string|max:20',
            'supported_service_levels' => 'required|string', // comma-separated string
            'service_areas' => 'required|string',            // comma-separated string
            'base_rate_usd' => 'required|numeric|min:0',
            'max_weight_kg' => 'required|numeric|min:0',
            'tracking_url_template' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
           $validated['user_id'] = auth()->id();

        // Convert comma-separated strings to JSON arrays for storage
        $validated['supported_service_levels'] = json_encode(
            array_map('trim', explode(',', $validated['supported_service_levels']))
        );

        $validated['service_areas'] = json_encode(
            array_map('trim', explode(',', $validated['service_areas']))
        );

        // Checkbox handling (if unchecked it won't be present in request)
        $validated['is_active'] = $request->has('is_active');

        Carrier::create($validated);

        return redirect()->route('distributionandlogistics.carriers.dashboard')
            ->with('success', "Carrier {$validated['name']} created successfully");
    }


    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:carriers',
            'contact_phone' => 'required|string|max:20',
            'supported_service_levels' => 'required|string', // comma-separated string
            'service_areas' => 'required|string',            // comma-separated string
            'base_rate_usd' => 'required|numeric|min:0',
            'max_weight_kg' => 'required|numeric|min:0',
            'tracking_url_template' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
           $validated['user_id'] = auth()->id();

        // Convert comma-separated strings to JSON arrays for storage
        $validated['supported_service_levels'] = json_encode(
            array_map('trim', explode(',', $validated['supported_service_levels']))
        );

        $validated['service_areas'] = json_encode(
            array_map('trim', explode(',', $validated['service_areas']))
        );

        // Checkbox handling (if unchecked it won't be present in request)
        $validated['is_active'] = $request->has('is_active');

        Carrier::create($validated);

        return redirect()->route('distributionandlogistics.admin.index')
            ->with('success', "Carrier {$validated['name']} created successfully");
    }
    public function edit(Carrier $carrier)
{
    return view('distributionandlogistics.carriers.edit', compact('carrier'));
}

public function update(UpdateCarrierRequest $request, Carrier $carrier)
{
    $validated = $request->validated();
    

    $carrier->update([
        'contact_phone' => $validated['contact_phone'],
        'name' => $validated['name'],
        'code' => $validated['code'],
        'supported_service_levels' => json_encode(array_map('trim', explode(',', $validated['supported_service_levels'] ?? ''))),
        'service_areas' => json_encode(array_map('trim', explode(',', $validated['service_areas'] ?? ''))),
        'base_rate_usd' => $validated['base_rate_usd'],
        'max_weight_kg' => $validated['max_weight_kg'],
        'tracking_url_template' => $validated['tracking_url_template'],
        'is_active' => $request->has('is_active'),
    ]);

    return redirect()->route('distributionandlogistics.admin.index')
        ->with('success', 'Carrier updated successfully.');
}
public function show(Carrier $carrier)
{
    
    return view('distributionandlogistics.carriers.show', compact('carrier'));
}


}
