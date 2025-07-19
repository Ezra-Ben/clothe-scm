<?php

namespace App\Http\Controllers\Logistics;

use App\Models\Carrier;
use Illuminate\Http\Request;
use App\Models\InboundShipment;
use App\Services\CarrierService;
use App\Models\ProcurementRequest;
use App\Http\Controllers\Controller;
use App\Notifications\CarrierAssignedShipmentNotification;

class InboundShipmentController extends Controller
{
    protected $carrierService;

    public function __construct(CarrierService $carrierService)
    {
        $this->carrierService = $carrierService;
    }
    public function index()
    {
        $shipments = InboundShipment::with(['carrier', 'supplier', 'procurementRequest'])->latest()->get();
        return view('logistics.inbound.index', compact('shipments'));
    }

    public function show($id, Request $request)
    {
        // Always list all carriers, filter if any filter is set
        if ($request->filled('service_area') || $request->filled('vehicle_type') || $request->filled('required_quantity')) {
            $carriers = $this->carrierService->filterCarriers(
                $request->input('service_area'),
                $request->input('vehicle_type'),
                $request->input('required_quantity')
            );
        } else {
            $carriers = Carrier::all();
        }

        // Set is_busy property for each carrier directly from carrier status field
        foreach ($carriers as $carrier) {
            $carrier->is_busy = ($carrier->status === 'busy');
        }

        $inboundShipment = InboundShipment::with(['supplier', 'carrier', 'procurementRequest'])->findOrFail($id);

        return view('logistics.inbound.show', compact('inboundShipment', 'carriers'));
    }

    public function assignCarrier(InboundShipment $inboundShipment, Carrier $carrier)
    {
        $inboundShipment->update([
            'carrier_id' => $carrier->id,
            'status' => 'in_transit',
            'tracking_number' => 'IB-' . now()->timestamp
        ]);
        $carrier->update(['status'=> 'busy']);
        $carrier->user->notify(new CarrierAssignedShipmentNotification($inboundShipment, 'inbound'));
        return redirect()->route('inbound.index')->with('success', 'Carrier assigned successfully!');
    }
    // AJAX endpoint for modal live filtering
    public function filterCarriers($shipmentId, Request $request)
    {
        if ($request->filled('service_area') || $request->filled('vehicle_type') || $request->filled('required_quantity')) {
            $carriers = $this->carrierService->filterCarriers(
                $request->input('service_area'),
                $request->input('vehicle_type'),
                $request->input('required_quantity')
            );
        } else {
            $carriers = Carrier::all();
        }
        foreach ($carriers as $carrier) {
            $carrier->is_busy = ($carrier->status === 'busy');
        }
        // Use a partial for the table body, pass assignCarrierPostRoute closure
        return view('logistics.partials.carrier_table', [
            'carriers' => $carriers,
            'assignCarrierPostRoute' => function($carrier) use ($shipmentId) {
                return route('logistics.inbound.assignCarrier', [$shipmentId, $carrier->id]);
            }
        ])->render();
    }

    public function updateStatus(Request $request, InboundShipment $shipment)
    {
        $request->validate([
            'status' => 'required|in:pending,in_transit,delivered',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'delivered') {
            $data['actual_delivery_date'] = now();
        }

        $shipment->update($data);


        return redirect()->route('inbound.show', $shipment)->with('success', 'Status updated.');
    }

}
