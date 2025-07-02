<?php

namespace App\Http\Controllers;

use App\Models\InboundShipment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Delivery;
use App\Models\Carrier;

class CarrierDashboardController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware('can:carrier'); // Only users with role 'carrier'
    }

 public function index(){
     // Fetch the carrier linked to the logged-in user
    $carrier = Carrier::where('user_id', auth()->id())->with('deliveries')->firstOrFail();

    // Use the carrier's ID for filtering shipments and deliveries
    $carrierId = $carrier->id;

    $shipments = InboundShipment::with(['supplier', 'carrier'])
        ->where('carrier_id', $carrierId)
        ->latest()
        ->paginate(10);

    $deliveries = Delivery::with(['carrier'])
        ->where('carrier_id', $carrierId)
        ->latest()
        ->paginate(10);

    return view('distributionandlogistics.carriers.dashboard', compact('shipments', 'deliveries', 'carrier'));
}

    // GET: /carrier/shipments/{id}
    public function show($id)
    {
        $shipment = InboundShipment::with('order.customer')->findOrFail($id);

        // Make sure shipment belongs to the logged-in carrier
        if ($shipment->carrier_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('carrier.show', compact('shipment'));
    }

    // PUT: /carrier/shipments/{id}
    public function update(Request $request, $id)
    {
        $shipment = InboundShipment::findOrFail($id);

        if ($shipment->carrier_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,dispatched,in_transit,out_for_delivery,delivered,failed',
            'estimated_delivery' => 'nullable|date',
            'actual_delivery' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $shipment->update($validated);

        return redirect()->route('carriers.dashboard')->with('success', 'Shipment updated successfully.');
    }
}
