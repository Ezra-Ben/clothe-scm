<?php
namespace App\Http\Controllers;

use App\Models\{Delivery, Order, Carrier};
use App\Http\Requests\UpdateDeliveryRequest;
use App\Http\Requests\StoreDeliveryRequest;
use App\Http\Requests\ChangeDeliveryStatusRequest;
use App\Services\DeliveryService;
use App\Http\Requests\UpdateDeliveryCarrierRequest;
use App\Http\Requests\UpdateDeliveryAddressRequest;

class DeliveryController extends Controller
{
    public function __construct(protected DeliveryService $deliveryService) {}

    public function create()
    {
        return view('distributionandlogistics.deliveries.form', [
            'delivery' => new Delivery(),
            'orders' => Order::all(),
            'carriers' => Carrier::all()
        ]);
    }

   

    public function store(StoreDeliveryRequest $request)
    {
        $validated = $request->validated();
        $order = Order::findOrFail($validated['order_id']);
        $carrier = Carrier::findOrFail($validated['carrier_id']);

        $delivery = $this->deliveryService->initiateDelivery(
            $order,
            $carrier,
            $validated['service_level']
        );

        return redirect()->route('distributionandlogistics.admin.index')
            ->with('success', 'Delivery created successfully');
    }

    

    public function edit(Delivery $delivery)
{
    $carriers = Carrier::all();
    return view('distributionandlogistics.deliveries.edit', compact('delivery', 'carriers'));
}

public function update(UpdateDeliveryRequest $request, Delivery $delivery)
{
    $validated = $request->validate([
        'carrier_id' => 'nullable|exists:carriers,id',
        'tracking_number' => 'required|string|max:255|unique:deliveries,tracking_number,' . $delivery->id,
        'status' => 'required|in:pending,processing,dispatched,in_transit,out_for_delivery,delivered,failed',
        'service_level' => 'required|string',
        'estimated_delivery' => 'nullable|date',
        'actual_delivery' => 'nullable|date',
        'notes' => 'nullable|string',
    ]);

    $delivery->update($validated);

    return redirect()->route('distributionandlogistics.admin.index')
        ->with('success', 'Delivery updated successfully.');
}

    public function changeStatusForm(Delivery $delivery)
{
    return view('distributionandlogistics.deliveries.change-status', compact('delivery'));
}
public function show(Delivery $delivery)
{
    
    return view('distributionandlogistics.deliveries.show', compact('delivery'));
}

public function updateStatus(ChangeDeliveryStatusRequest $request, Delivery $delivery)
{
    $allowedStatuses = [
        'pending', 
        'processing', 
        'dispatched', 
        'in_transit', 
        'out_for_delivery', 
        'delivered', 
        'failed'
    ];

    $request->validate([
        'status' => ['required', 'string', 'in:' . implode(',', $allowedStatuses)],
    ]);

    $newStatus = $request->input('status');

    if ($delivery->status !== $newStatus) {
        $delivery->status = $newStatus;
        $delivery->save();

        $delivery->statusHistories()->create([
            'status' => $newStatus,
            'changed_at' => now(),
        ]);
    }

    return redirect()->route('distributionandlogistics.admin.index', $delivery->id)
                     ->with('success', 'Delivery status updated successfully.');
}
public function carrierEdit(Delivery $delivery)
{
    $this->authorize('carrier-only');

    return view('distributionandlogistics.deliveries.carrier-edit', compact('delivery'));
}

public function carrierUpdate(UpdateDeliveryCarrierRequest $request, Delivery $delivery)
{
    $this->authorize('carrier-only');

    $validated = $request->validate([
        'status' => 'required|string|in:pending,processing,dispatched,in_transit,out_for_delivery,delivered,failed',
        'estimated_delivery' => 'nullable|date',
    ]);

    $delivery->update($validated);

    return redirect()->route('carriers.dashboard')->with('success', 'Delivery updated successfully.');
}

public function updateAddress(UpdateDeliveryAddressRequest $request, Delivery $delivery)
{
   $validated = $request->validate([
        'new_address' => 'required|string|max:255',
    ]);

    // Update the shipping_address on the related customer
    $delivery->order->customer->update([
        'shipping_address' => $validated['new_address'],
    ]);

    return redirect()->route('distributionandlogistics.users.dashboard')->with('success', 'Delivery address updated.');
}

public function changeAddressForm(Delivery $delivery){


    $currentAddress = $delivery->order->customer->shipping_address;

    return view('distributionandlogistics.deliveries.change-address', [
        'delivery' => $delivery,
        'currentAddress' => $currentAddress,
    ]);
}

}
