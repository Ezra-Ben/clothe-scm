<?php

namespace App\Http\Controllers\Logistics;

use App\Models\Order;
use App\Models\Carrier;
use Illuminate\Http\Request;
use App\Models\InboundShipment;
use App\Models\OutboundShipment;
use App\Services\CarrierService;
use App\Http\Controllers\Controller;
use App\Notifications\CarrierAssignedShipmentNotification;

class OutboundShipmentController extends Controller
{
    protected $carrierService;

    public function __construct(CarrierService $carrierService)
    {
        $this->carrierService = $carrierService;
    }

    public function index()
    {
        $shipments = OutboundShipment::with(['order.customer.user', 'carrier.user'])
            ->whereIn('status', ['pending', 'in_transit', 'delivered'])
            ->get();

        return view('logistics.outbound.index', compact('shipments'));
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

        $shipment = OutboundShipment::with(['order.items.product', 'order.customer', 'carrier'])->findOrFail($id);

        return view('logistics.outbound.show', compact('shipment', 'carriers'));
    }

    public function assignCarrier(OutboundShipment $shipment, Carrier $carrier)
    {
        $shipment->update([
            'carrier_id' => $carrier->id,
            'status' => 'in_transit',
        ]);

        $carrier->update(['status'=> 'busy']);

        if ($shipment->order && $shipment->order->fulfillment) {
            $shipment->order->fulfillment()->update(['status' => 'in_transit']);
        }

        $carrier->user->notify(new CarrierAssignedShipmentNotification($shipment, 'outbound'));

        return redirect()->route('outbound.index')->with('success', 'Carrier assigned successfully!');
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
                return route('logistics.outbound.assignCarrier', [$shipmentId, $carrier->id]);
            }
        ])->render();
    }

}
