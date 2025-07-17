<?php

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OutboundShipment;
use App\Models\InboundShipment;
use App\Models\Carrier;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class LogisticsController extends Controller
{
    public function index()
    {
        $readyCount = OutboundShipment::where('status', 'ready')->count();
        $inTransitCount = OutboundShipment::where('status', 'in_transit')->count();
        $deliveredCount = OutboundShipment::where('status', 'delivered')->count();
        $totalCarriers = Carrier::count();

        // Example: Carrier batches (customize as needed)
        $carrierBatches = Carrier::withCount(['shipments as batch_count' => function($q) {
            $q->where('status', '!=', 'delivered');
        }])->with(['shipments' => function($q) {
            $q->select('carrier_id', 'destination', 'status');
        }])->get()->map(function($carrier) {
            return (object) [
                'carrier' => $carrier,
                'destination' => optional($carrier->shipments->first())->destination,
                'status' => optional($carrier->shipments->first())->status,
                'batch_count' => $carrier->batch_count,
            ];
        });

        // Recent deliveries
        $recentShipments = OutboundShipment::with(['order.customer', 'carrier'])
            ->where('status', 'delivered')
            ->orderByDesc('actual_delivery_date')
            ->take(10)
            ->get();

        return view('logistics.dashboard', [
            'readyCount' => $readyCount,
            'inTransitCount' => $inTransitCount,
            'deliveredCount' => $deliveredCount,
            'totalCarriers' => $totalCarriers,
            'carrierBatches' => $carrierBatches,
            'recentShipments' => $recentShipments,
        ]);
    }
}
