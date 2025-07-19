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
        $readyCount = OutboundShipment::where('status', 'pending')->count() + InboundShipment::where('status', 'pending')->count();
        $inTransitCount = OutboundShipment::where('status', 'in_transit')->count() + InboundShipment::where('status', 'in_transit')->count();
        $deliveredCount = OutboundShipment::where('status', 'delivered')->count() + InboundShipment::where('status', 'delivered')->count();
        $totalCarriers = Carrier::count();

        // Carrier batches: combine outbound and inbound shipments
        $carrierBatches = Carrier::with(['outboundShipments' => function($q) {
            $q->select('carrier_id', 'destination', 'status');
        }, 'inboundShipments' => function($q) {
            $q->select('carrier_id', 'status'); // inbound does not have destination
        }])->get()->map(function($carrier) {
            $outboundInTransit = $carrier->outboundShipments->where('status', 'in_transit')->first();
            $outbound = $carrier->outboundShipments->where('status', '=', 'in_transit');
            $inbound = $carrier->inboundShipments->where('status', '=', 'in_transit');
            $allShipments = $outbound->concat($inbound);
            return (object) [
                'carrier' => $carrier,
                'destination' => $outboundInTransit ? $outboundInTransit->destination : '',
                'status' => optional($allShipments->first())->status,
                'batch_count' => $allShipments->count(),
            ];
        });

        // Recent outbound deliveries
        $recentOutboundShipments = OutboundShipment::with(['order.customer', 'carrier'])
            ->where('status', 'delivered')
            ->orderByDesc('actual_delivery_date')
            ->take(10)
            ->get();

        // Recent inbound deliveries
        $recentInboundShipments = InboundShipment::with(['procurementRequest', 'supplier', 'carrier'])
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
            'recentOutboundShipments' => $recentOutboundShipments,
            'recentInboundShipments' => $recentInboundShipments,
        ]);
    }
}
