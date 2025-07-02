<?php

namespace App\Http\Controllers;

use App\Models\InboundShipment;
use App\Models\Delivery;
use App\Models\Carrier;

class DistributionAndLogisticsController extends Controller
{
    public function index()
    {
        return view('distributionandlogistics.admin.index', [
        'shipments' => InboundShipment::with(['supplier', 'carrier'])->latest()->paginate(5, ['*'], 'shipments'),
        'deliveries' => Delivery::with('carrier')->latest()->paginate(5, ['*'], 'deliveries'),
        'carriers' => Carrier::with('deliveries')->latest()->paginate(5, ['*'], 'carriers'), // Added latest()
    ]);
    }
}

