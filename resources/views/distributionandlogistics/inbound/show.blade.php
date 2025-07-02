@extends('layouts.app')

@section('title', 'Inbound Shipment Details')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Inbound Shipment #{{ $shipment->id }}</h5>
        <a href="{{ route('distributionandlogistics.admin.index') }}" class="btn btn-sm btn-secondary">← Back</a>
    </div>

    <div class="card-body">
        <p><strong>Tracking Number:</strong> <code>{{ $shipment->tracking_number }}</code></p>
        <p><strong>Status:</strong> <span class="badge bg-{{ $shipment->status_badge }}">{{ ucfirst($shipment->status) }}</span></p>
        <p><strong>Supplier Order:</strong> #{{ $shipment->supplier_order_id }} ({{ optional($shipment->supplierOrder->supplier)->name ?? 'N/A' }})</p>
        <p><strong>Carrier:</strong> {{ optional($shipment->carrier)->name ?? 'N/A' }}</p>
        <p><strong>Estimated Arrival:</strong> {{ $shipment->estimated_arrival->format('M d, Y H:i') }}</p>

        <hr>
        <h6>Status History</h6>
        @if($shipment->statusHistories->count())
            <ul class="list-group">
                @foreach($shipment->statusHistories as $history)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ ucfirst($history->status) }}</span>
                        <small class="text-muted">{{ $history->changed_at->format('M d, Y H:i') }}</small>
                    </li>
                @endforeach
            </ul>
        @else
            <p>No status updates recorded yet.</p>
        @endif
    </div>
</div>
@endsection
