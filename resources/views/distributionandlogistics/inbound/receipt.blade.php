@extends('layouts.app')

@section('title', 'Receiving Report')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Receiving Report – Shipment #{{ $shipment->id }}</h5>
    </div>
    <div class="card-body">
        <p><strong>Carrier:</strong> {{ $shipment->carrier->name ?? 'N/A' }}</p>
        <p><strong>Tracking Number:</strong> {{ $shipment->tracking_number }}</p>
        <p><strong>Status:</strong> {{ ucfirst($shipment->status) }}</p>
        <p><strong>Received At:</strong> 
            {{ optional($report->received_at)->format('M d, Y H:i') ?? 'Not Available' }}
        </p>
        <p><strong>Condition:</strong> {{ $report->condition ?? 'Not Available' }}</p>
        <p><strong>Discrepancy Notes:</strong> 
            {{ $report->discrepancy_notes ?? 'None' }}
        </p>

        <hr>

        @if (!empty($shipment->received_items))
            <h6>Items Received:</h6>
            <ul>
                @foreach ($shipment->received_items as $item)
                    <li>{{ $item['name'] ?? 'Unnamed Item' }} - Qty: {{ $item['quantity'] ?? 'N/A' }}</li>
                @endforeach
            </ul>
        @else
            <p>No items received.</p>
        @endif

        <hr>
        <a href="#" onclick="window.print()" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-printer"></i> Print
        </a>
    </div>
</div>
@endsection
