@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-3">Inbound Shipment #{{ $inboundShipment->id }} Details</h3>
                    <div class="row mb-2">
                        <div class="col-md-6 mb-2">
                            <strong>Supplier:</strong> {{ $inboundShipment->supplier->vendor->name ?? '-' }}<br>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Status:</strong> <span class="badge bg-{{ $inboundShipment->status === 'pending' ? 'secondary' : ($inboundShipment->status === 'in_transit' ? 'warning' : 'success') }}">{{ ucfirst(str_replace('_', ' ', $inboundShipment->status ?? '-')) }}</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 mb-2">
                            <strong>Address:</strong> {{ $inboundShipment->supplier->address ?? '-' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            @php $status = $inboundShipment->status; @endphp
                            @if ($status === 'in_transit')
                                <strong>Estimated Delivery:</strong> {{ $inboundShipment->estimated_delivery_date?->format('Y-m-d') ?? '-' }}
                            @elseif ($status === 'delivered')
                                <strong>Actual Delivery:</strong> {{ $inboundShipment->actual_delivery_date?->format('Y-m-d H:i') ?? '-' }}
                            @endif
                        </div>
                        <div class="col-md-6 mb-2">
                            @if($status !== 'delivered')
                                <form method="POST" action="{{ route('logistics.inbound.updateStatus', $inboundShipment->id) }}" class="mb-0">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group" style="max-width: 300px;">
                                        <label class="input-group-text" for="statusSelect">Update Status</label>
                                        <select name="status" id="statusSelect" class="form-select">
                                            <option value="in_transit" {{ $status === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                            <option value="delivered" {{ $status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 d-flex flex-column align-items-stretch justify-content-center" style="height:100%">
            @if($inboundShipment->status === 'pending')
                <button class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#assignCarrierModal">
                    <i class="bi bi-truck me-2"></i>Assign Carrier
                </button>
            @endif
            @if($inboundShipment->carrier)
                <div class="card w-100" style="margin-bottom:0">
                    <div class="card-body p-3">
                        <h5 class="card-title mb-2"><i class="bi bi-truck me-2"></i>Carrier Assigned</h5>
                        <p class="mb-1"><strong>Name:</strong> {{ $inboundShipment->carrier->user->name }}</p>
                        <p class="mb-1"><strong>Contact:</strong> {{ $inboundShipment->carrier->contact_phone ?? '-' }}<br>
                        <strong>Email:</strong> {{ $inboundShipment->carrier->user->email ?? '-' }}</p>
                    </div>
                </div>
            @endif
            @if(auth()->check() && $inboundShipment->carrier && auth()->user()->id === $inboundShipment->carrier->user_id &&
                !$inboundShipment->pod)
                <a href="{{ route('pods.create', ['shipment' => $inboundShipment->id, 'type' => 'App\Models\InboundShipment']) }}" class="btn btn-primary w-100 mt-2">
                    <i class="bi bi-file-earmark-check me-2"></i>Submit Proof of Delivery
                </a>
            @endif
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Request Items</h5>
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @if($inboundShipment->procurementRequest)
                    <li class="list-group-item d-flex align-items-center justify-content-between">
                        <span class="fw-semibold">{{ $inboundShipment->procurementRequest->rawMaterial->name ?? '-' }}</span>
                        <span class="badge bg-secondary">Qty: {{ $inboundShipment->procurementRequest->quantity ?? '-' }}</span>
                    </li>
                @else
                    <li class="list-group-item text-muted">No procurement request found.</li>
                @endif
            </ul>
        </div>
    </div>

    @include('logistics.partials.modal', [
        'shipment' => $inboundShipment,
        'assignCarrierAction' => route('inbound.show', $inboundShipment->id),
        'carriers' => $carriers,
        'assignCarrierPostRoute' => fn($carrier) => route('logistics.inbound.assignCarrier', [$inboundShipment->id, $carrier->id]),
    ])
</div>
@endsection
