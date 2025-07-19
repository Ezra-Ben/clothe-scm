<?php

namespace App\Http\Controllers\Logistics;

use App\Models\Pod;
use App\Models\User;
use App\Models\InboundShipment;
use App\Models\OutboundShipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Notifications\PodSubmittedNotification;

class PodController extends Controller
{
    public function index()
    {
        $pods = Pod::with('shipment')->latest()->get();
        return view('logistics.pods.index', compact('pods'));
    }

    public function show(Pod $pod)
    {
        $pod->load('shipment');
        return view('logistics.pods.show', compact('pod'));
    }

    public function create(Request $request)
    {
        $shipmentId = $request->get('shipment');
        $shipmentType = $request->get('type'); 

        if ($shipmentType === 'App\Models\InboundShipment') {
            $shipment = \App\Models\InboundShipment::findOrFail($shipmentId);
        } else {
            $shipment = \App\Models\OutboundShipment::findOrFail($shipmentId);
        }

        return view('logistics.pods.create', compact('shipment', 'shipmentType'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipment_id' => 'required|integer',
            'shipment_type' => 'required|in:App\Models\InboundShipment,App\Models\OutboundShipment',
            'delivered_by' => 'required|string|max:255',
            'received_by' => 'required|string|max:255',
            'received_at' => 'nullable|date',
            'delivery_notes' => 'nullable|string|max:1000',
            'recipient_name' => 'nullable|string|max:255',
            'condition' => 'nullable|string|max:255',
            'discrepancies' => 'nullable|string|max:1000',
            'confirm_delivery' => 'sometimes|accepted',
            'rating' => 'required|integer|min:1|max:10'
        ]);

        DB::beginTransaction();

        try {

            $pod = Pod::create([
                'shipment_id' => $request->shipment_id,
                'shipment_type' => $request->shipment_type,
                'delivered_by' => $request->delivered_by,
                'received_by' => $request->received_by,
                'received_at' => $request->received_at ?? now(),
                'delivery_notes' => $request->delivery_notes,
                'recipient_name' => $request->recipient_name,
                'condition' => $request->condition,
                'discrepancies' => $request->discrepancies,
            ]);

            if ($request->has('confirm_delivery')) {
                $shipmentClass = $request->shipment_type;
                $shipment = $shipmentClass::findOrFail($request->shipment_id);

                $shipment->update([
                    'status' => 'delivered',
                    'actual_delivery_date' => now(),
                ]);

                if ($shipment instanceof OutboundShipment) {
                    $order = $shipment->order;
                    if ($order) {
                        $order->fulfillment()->update(['status' => 'complete']);
                    }
                }

                $carrier = $shipment->carrier;  

            if ($carrier) {
                $oldRating = $carrier->customer_rating ?? 0;
                $newRating = $request->rating;

                // If there is no previous rating, just use the new one
            if ($oldRating == 0) {
                $avgRating = $newRating;
            } else {
                $avgRating = round((($oldRating + $newRating) / 2), 2);
            }

            $carrier->update([
                'customer_rating' => $avgRating,
                'status' => 'free',
            ]);
            }
            
            $logisticsManager = User::all()->first(function ($user) {
            return $user->hasRole('admin');
            });

            if ($logisticsManager) {
                $logisticsManager->notify(new PodSubmittedNotification($pod));  
            }

        }
            DB::commit();

            return redirect()->route('carrier.dashboard')->with('success', 'Proof of Delivery submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to submit PoD: ' . $e->getMessage()]);
        }
    }
}
