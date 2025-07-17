@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Inbound Shipment #{{ $inboundShipment->id }}</h3>

    <div class="mb-3">
        <strong>Supplier:</strong> {{ $inboundShipment->supplier->name ?? '-' }} <br>
        <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $inboundShipment->status)) }}
        @php
          $status = $inboundShipment->status;
        @endphp

        @if ($status === 'in_transit')
            <strong>Estimated Delivery:</strong> {{ $inboundShipment->estimated_delivery_date?->format('Y-m-d') ?? '-' }} <br>
        @elseif ($status === 'delivered')
            <strong>Actual Delivery:</strong> {{ $inboundShipment->actual_delivery_date?->format('Y-m-d H:i') ?? '-' }} <br>
        @endif
        {{-- Status update dropdown --}}
        @if($status !== 'delivered')
            <form method="POST" action="{{ route('logistics.inbound.updateStatus', $inboundShipment->id) }}" class="mb-3">
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
        @else
            <span class="badge bg-success">Delivered</span>
        @endif
    </div>

    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignCarrierModal">
        Assign Carrier
    </button>

    @include('logistics.partials.modal', [
        'assignCarrierAction' => route('logistics.orders.inbound.show', $inboundShipment->id),
        'carriers' => $carriers,
        'assignCarrierPostRoute' => fn($carrier) => route('logistics.orders.inbound.assign_carrier_post', [$inboundShipment->id, $carrier->id]),
    ])
    
    @if(auth()->check()
        && $inboundShipment->carrier
        && auth()->user()->id === $inboundShipment->carrier->user_id)
        <a href="{{ route('pods.create', ['shipment' => $inboundShipment->id]) }}" class="btn btn-primary mt-3">
            Submit Proof of Delivery
        </a>
    @endif
 
</div>
@endsection
