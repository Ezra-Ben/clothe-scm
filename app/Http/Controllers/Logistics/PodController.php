<?php

namespace App\Http\Controllers\Logistics;

use App\Models\Pod;
use App\Models\InboundShipment;
use App\Models\OutboundShipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
        // Get the carrier linked to the currently logged-in user
        $carrier = $request->user()->carrier;

        if (!$carrier) {
            abort(403, 'No carrier profile linked to your user account.');
        }

        // Get inbound shipments assigned to this carrier that are not yet delivered
        $inboundShipments = $carrier->inboundShipments()
            ->whereIn('status', ['pending', 'in_transit'])
            ->get();

        // Get outbound shipments assigned to this carrier that are not yet delivered
        $outboundShipments = $carrier->outboundShipments()
            ->whereIn('status', ['pending', 'in_transit'])
            ->get();

        // Combine both collections
        $shipments = $inboundShipments->concat($outboundShipments);

        return view('logistics.pods.create', compact('shipments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipment_id' => 'required|integer',
            'shipment_type' => 'required|string|in:inbound_shipment,outbound_shipment',
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
                $shipmentClass = $request->shipment_type === 'inbound_shipment' ? InboundShipment::class : OutboundShipment::class;
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
                $oldAvg = $carrier->rating ?? 0;
                $oldCount = $carrier->rating_count ?? 0;
                $newRating = $request->rating;

                $newAvg = (($oldAvg * $oldCount) + $newRating) / ($oldCount + 1);

                $carrier->update([
                    'rating' => round($newAvg, 2),
                    'rating_count' => $oldCount + 1,
                ]);
            }
        

            }

            DB::commit();

            return redirect()->route('logistics.pods.index')->with('success', 'Proof of Delivery submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to submit PoD: ' . $e->getMessage()]);
        }
    }
}
