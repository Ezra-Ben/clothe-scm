@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-3">Order #{{ $shipment->order->id ?? '-' }} Details</h3>
                    <div class="row mb-2">
                        <div class="col-md-6 mb-2">
                            <strong>Customer:</strong> {{ $shipment->order->customer->user->name ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Shipping Address:</strong> {{ $shipment->order->customer->shipping_address ?? '-' }}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 mb-2">
                            <strong>Status:</strong> <span class="badge bg-{{ $shipment->status === 'pending' ? 'primary' : ($shipment->status === 'in_transit' ? 'warning' : 'success') }}">{{ ucfirst(str_replace('_', ' ', $shipment->status ?? '-')) }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            @php $status = $shipment->status; @endphp
                            @if ($status === 'in_transit')
                                <strong>Estimated Delivery:</strong> {{ $shipment->estimated_delivery_date?->format('Y-m-d') ?? '-' }}
                            @elseif ($status === 'delivered')
                                <strong>Actual Delivery:</strong> {{ $shipment->actual_delivery_date?->format('Y-m-d H:i') ?? '-' }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex flex-column align-items-stretch justify-content-center" style="height:100%">
            @if($shipment->status === 'pending')
                <button class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#assignCarrierModal">
                    <i class="bi bi-truck me-2"></i>Assign Carrier
                </button>
            @endif
            @if($shipment->carrier)
                <div class="card w-100" style="margin-bottom:0">
                    <div class="card-body p-3">
                        <h5 class="card-title mb-2"><i class="bi bi-truck me-2"></i>Carrier Assigned</h5>
                        <p class="mb-1"><strong>Name:</strong> {{ $shipment->carrier->user->name }}</p>
                        <p class="mb-1"><strong>Contact:</strong> {{ $shipment->carrier->contact_phone ?? '-' }}<br>
                        <strong>Email:</strong> {{ $shipment->carrier->user->email ?? '-' }}</p>
                    </div>
                </div>
            @endif
            @if(auth()->check() && $shipment->carrier && auth()->user()->id === $shipment->carrier->user_id &&
                !$shipment->pod)
                <a href="{{ route('pods.create', ['shipment' => $shipment->id, 'type' => 'App\Models\OutboundShipment']) }}" class="btn btn-primary w-100 mt-2">
                    <i class="bi bi-file-earmark-check me-2"></i>Submit Proof of Delivery
                </a>
            @endif
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Order Items</h5>
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @foreach($shipment->order->items as $item)
                    <li class="list-group-item d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <img src="{{ $item->product->image ? asset('storage/products/' . $item->product->image) : 'https://via.placeholder.com/40' }}" alt="{{ $item->product->name }}" class="rounded me-3" style="width:40px;height:40px;object-fit:cover;">
                            <span class="fw-semibold">{{ $item->product->name }}</span>
                        </div>
                        <span class="badge bg-secondary">Qty: {{ $item->quantity }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    @include('logistics.partials.modal', [
        'assignCarrierAction' => route('outbound.show', $shipment->id),
        'carriers' => $carriers,
        'assignCarrierPostRoute' => fn($carrier) => route('logistics.outbound.assignCarrier', [$shipment->id, $carrier->id]),
    ])
</div>
@endsection
