@extends('layouts.app')

@section('title', 'Delivery Details')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Delivery #{{ $delivery->id }}</h5>
        <a href="{{ route('distributionandlogistics.admin.index') }}" class="btn btn-sm btn-secondary">← Back</a>
    </div>

    <div class="card-body">
        <p><strong>Tracking Number:</strong> <code>{{ $delivery->tracking_number }}</code></p>
        <p><strong>Status:</strong> <span class="badge bg-{{ $delivery->status_color ?? 'secondary' }}">{{ ucfirst(str_replace('_', ' ', $delivery->status)) }}</span></p>
        <p><strong>Service Level:</strong> {{ ucfirst($delivery->service_level) }}</p>
        <p><strong>Order ID:</strong> {{ $delivery->order_id }}</p>
        <p><strong>Carrier:</strong> {{ optional($delivery->carrier)->name ?? 'N/A' }}</p>
        <p><strong>Estimated Delivery:</strong> {{ optional($delivery->estimated_delivery)->format('M d, Y H:i') }}</p>
        <p><strong>Actual Delivery:</strong> {{ optional($delivery->actual_delivery)->format('M d, Y H:i') ?? 'Not yet delivered' }}</p>
        <p><strong>Notes:</strong> {{ $delivery->notes ?? 'None' }}</p>

        <!-- Shipping Address -->
        <p><strong>Shipping Address:</strong> 
             {{ optional(optional($delivery->order)->customer)->shipping_address ?? 'N/A' }}
        </p>

        <hr>
        <h6>Status History</h6>
        @if($delivery->statusHistories->count())
            <ul class="list-group">
                @foreach($delivery->statusHistories as $history)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ ucfirst(str_replace('_', ' ', $history->status)) }}</span>
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
