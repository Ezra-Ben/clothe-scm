<?php

namespace App\Http\Controllers;

use App\Models\InboundShipment;
use App\Models\InboundShipmentStatusHistory;
use App\Http\Requests\ReceiveShipmentRequest;
use App\Services\InboundLogisticsService;
use App\Models\Supplier;
use App\Models\SupplierOrder; 
use App\Models\Carrier;
use App\Http\Requests\StoreShipmentRequest;
use App\Http\Requests\UpdateInboundShipmentRequest;
use App\Http\Requests\ChangeInboundShipmentStatusRequest;
use App\Http\Requests\UpdateInboundShipmentCarrierRequest;

class InboundShipmentController extends Controller
{
    public function __construct(
        private InboundLogisticsService $service
    ) {}

    public function index()
    {
        $shipments = InboundShipment::with(['supplier', 'carrier'])
            ->latest()
            ->paginate(10);

        return view('distributionandlogistics.admin.index', compact('shipments'));
    }

    

    public function receive(ReceiveShipmentRequest $request, InboundShipment $shipment)
    {
        $report = $this->service->receiveShipment(
            $shipment,
            $request->user(),
            $request->validated()
        );

        return redirect()->route('distributionandlogistics.admin.index')
            ->with('success', 'Shipment received successfully');
    }

    public function create()
{
    return view('distributionandlogistics.inbound.create', [
        'supplierOrders' => SupplierOrder::with('supplier')->get(),
        'carriers' => Carrier::all()
    ]);
}
public function show(InboundShipment $shipment)
{
    return view('distributionandlogistics.inbound.show', compact('shipment'));
}

public function store(StoreShipmentRequest $request)
{
    try {
        $validated = $request->validated();
        
        // Convert date format if needed
        $validated['estimated_arrival'] = $this->parseDate($validated['estimated_arrival']);
        
        $shipment = $this->service->createInboundShipment(
            SupplierOrder::findOrFail($validated['supplier_order_id']),
            $validated
        );

        return redirect()
            ->route('distributionandlogistics.admin.index')
            ->with('success', 'Shipment created.');
            
    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', 'Failed to create shipment: ' . $e->getMessage());
    }
}

private function parseDate($date)
{
    try {
        return \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $date);
    } catch (\Exception $e) {
        return \Carbon\Carbon::parse($date);
    }
}

public function showReceiveForm(InboundShipment $shipment)
{
    return view('distributionandlogistics.inbound.receive', compact('shipment'));
}
public function changeStatusForm(InboundShipment $shipment)
{
    return view('distributionandlogistics.inbound.change-status', compact('shipment'));
}

public function showReceipt(InboundShipment $shipment)
{
    $report = $shipment->receivingReport; // assuming relationship is defined

    return view('distributionandlogistics.inbound.receipt', [
        'shipment' => $shipment,
        'report' => $report,
    ]);
}



public function updateStatus(ChangeInboundShipmentStatusRequest $request, InboundShipment $shipment)
{
    $validated = $request->validate([
        'status' => 'required|in:processing,in_transit,arrived,received',
    ]);

    // Update the current status on the shipment
    $shipment->status = $validated['status'];
    $shipment->save();

    // Log the status change in the history table
    InboundShipmentStatusHistory::create([
        'inbound_shipment_id' => $shipment->id,
        'status' => $validated['status'],
        'changed_at' => now(),
    ]);

    return redirect()->route('distributionandlogistics.admin.index')->with('success', 'Shipment status updated successfully.');
}

public function edit(InboundShipment $shipment)
{
    $carriers = Carrier::all();
    return view('distributionandlogistics.inbound.edit', compact('shipment', 'carriers'));
}

public function update(UpdateInboundShipmentRequest $request, InboundShipment $shipment)
{
    $validated = $request->validate([
        'carrier_id' => 'required|exists:carriers,id',
        'tracking_number' => 'required|string|max:255',
        'estimated_arrival' => 'required|date',
        'status' => 'required|in:processing,in_transit,arrived,received',
    ]);

    $shipment->update($validated);

    return redirect()->route('distributionandlogistics.admin.index')
        ->with('success', 'Inbound shipment updated successfully.');
}
public function carrierEdit(InboundShipment $shipment)
{
    $this->authorize('carrier-only');

    return view('distributionandlogistics.inbound.carrier-edit', compact('shipment'));
}

public function carrierUpdate(UpdateInboundShipmentCarrierRequest $request, InboundShipment $shipment)
{
    $this->authorize('carrier-only');

    $validated = $request->validate([
        'status' => 'required|string|in:processing,in_transit,arrived,received',
        'estimated_arrival' => 'nullable|date',
    ]);

    $shipment->update($validated);

    return redirect()->route('carrier.dashboard')->with('success', 'Shipment updated successfully.');
}


}
