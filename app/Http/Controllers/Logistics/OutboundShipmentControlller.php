<?php

namespace App\Http\Controllers\Logistics;

use App\Models\Order;
use App\Models\Carrier;
use Illuminate\Http\Request;
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
        $orders = Order::with(['customer', 'fulfillment'])
            ->whereIn('status', ['ready_for_shipping', 'in_transit', 'delivered'])
            ->get();

        return view('logistics.outbound.index', compact('orders'));
    }

    public function show(Order $order, Request $request)
    {
        $carriers = [];

        if ($request->has('service_area')) {
            $carriers = $this->carrierService->filterCarriers(
                $request->input('service_area'),
                $request->input('vehicle_type'),
                $request->input('required_quantity')
            );
        }

        $order->load(['items.product', 'customer']);

        return view('logistics.outbound.show', compact('order', 'carriers'));
    }

    public function assignCarrier(Order $order, Carrier $carrier)
    {
        $shipment = $order->outboundShipment;

        if (!$shipment) {
            return redirect()->back()->withErrors('Shipment not yet created. Please wait for processing.');
        }

        $shipment->update([
            'carrier_id' => $carrier->id,
            'status' => 'in_transit',
        ]);

        $order->fulfillment()->update(['status' => 'in_transit']);

        $carrier->user->notify(new CarrierAssignedShipmentNotification($shipment, 'outbound'));

        return redirect()->route('logistics.orders.outbound.index')->with('success', 'Carrier assigned successfully!');
    }

}
