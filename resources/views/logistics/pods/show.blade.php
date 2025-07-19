@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">POD #{{ $pod->id }} Details</h3>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>
                Shipment Type:
                @if($pod->shipment_type === 'App\Models\InboundShipment')
                    Inbound
                @elseif($pod->shipment_type === 'App\Models\OutboundShipment')
                    Outbound
                @else
                    Unknown
                @endif
            </h5>
            <a href="{{ route('pods.index') }}" class="btn btn-sm btn-secondary">Back to POD List</a>
        </div>
        <div class="card-body">
            <div class="mb-3"><strong>Delivered By:</strong> {{ $pod->delivered_by }}</div>
            <div class="mb-3"><strong>Received By:</strong> {{ $pod->received_by }}</div>
            <div class="mb-3"><strong>Received At:</strong> {{ optional($pod->received_at)->format('d M Y H:i') }}</div>
            <div class="mb-3"><strong>Delivery Notes:</strong> {{ $pod->delivery_notes ?? 'N/A' }}</div>
            <div class="mb-3"><strong>Recipient Name:</strong> {{ $pod->recipient_name ?? 'N/A' }}</div>
            <div class="mb-3"><strong>Condition:</strong> {{ $pod->condition ?? 'N/A' }}</div>
            <div class="mb-3"><strong>Discrepancies:</strong> {{ $pod->discrepancies ?? 'N/A' }}</div>
        </div>
    </div>
</div>
@endsection
