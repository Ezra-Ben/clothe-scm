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

    public function show(InboundShipment $inboundShipment, Request $request)
    {
        $carriers = [];

        if ($request->has('service_area')) {
            $carriers = $this->carrierService->filterCarriers(
                $request->input('service_area'),
                $request->input('vehicle_type'),
                $request->input('required_quantity')
            );
        }

        $inboundShipment->load(['supplier', 'carrier', 'procurementRequest']);

        return view('logistics.inbound.show', compact('inboundShipment', 'carriers'));
    }

    public function assignCarrier(InboundShipment $inboundShipment, Carrier $carrier)
    {
        $inboundShipment->update([
            'carrier_id' => $carrier->id,
            'status' => 'in_transit',
            'tracking_number' => 'IB-' . now()->timestamp
        ]);
        $carrier->user->notify(new CarrierAssignedShipmentNotification($inboundShipment, 'inbound'));
        return redirect()->route('logistics.orders.inbound.index')->with('success', 'Carrier assigned successfully!');
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


        return redirect()->route('logistics.orders.inbound.show', $shipment)->with('success', 'Status updated.');
    }

}
