<?php

namespace App\Http\Controllers\Carrier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $carrier = Auth::user()->carrier;

        $toDoInbound = $carrier->inboundShipments()->where('status', '!=', 'delivered')->with('procurementRequest')->get();
        $completedInbound = $carrier->inboundShipments()->where('status', 'delivered')->with('procurementRequest')->get();

        $toDoOutbound = $carrier->outboundShipments()->where('status', '!=', 'delivered')->with('order')->get();
        $completedOutbound = $carrier->outboundShipments()->where('status', 'delivered')->with('order')->get();

        $notifications = Auth::user()->unreadNotifications;

        return view('carrier.dashboard', compact(
            'toDoInbound', 'completedInbound',
            'toDoOutbound', 'completedOutbound',
            'notifications'
        ));
    }
}
