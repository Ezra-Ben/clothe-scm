@extends('layouts.app')

@section('title', 'Order Details: ' . $order->id)

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-primary">Order #{{ $order->id }} Details</h1>

    <div class="card shadow-sm mb-4 bg-white">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Order Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Customer:</strong> {{ $order->customer->name ?? 'N/A' }}</p>
                    <p><strong>Product:</strong> {{ $order->product->name ?? 'N/A' }}</p>
                    <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-primary">{{ Str::title(str_replace('_', ' ', $order->status)) }}</span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Order Date:</strong> {{ $order->order_date->format('Y-m-d H:i') }}</p>
                    <p><strong>Production Start Date:</strong> {{ $order->production_start_date ? $order->production_start_date->format('Y-m-d H:i') : 'Not Started' }}</p>
                    <p><strong>Created At:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>Last Updated:</strong> {{ $order->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
            <hr>
            {{-- Add actions related to order (e.g., Change Order Status, Create Batch) --}}
            {{-- <button class="btn btn-outline-primary">Change Order Status</button> --}}
        </div>
    </div>

    <div class="card shadow-sm bg-white">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Associated Production Batches</h5>
        </div>
        <div class="card-body">
            @if($order->batches->count())
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Batch ID</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->batches as $batch)
                            <tr>
                                <td>{{ $batch->id }}</td>
                                <td>{{ $batch->quantity }}</td>
                                <td><span class="badge {{ $batch->status === 'in_progress' ? 'bg-info' : ($batch->status === 'completed' ? 'bg-success' : 'bg-secondary') }}">{{ Str::title(str_replace('_', ' ', $batch->status)) }}</span></td>
                                <td>{{ $batch->start_date ? $batch->start_date->format('Y-m-d') : 'N/A' }}</td>
                                <td>{{ $batch->end_date ? $batch->end_date->format('Y-m-d') : 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('production.batches.show', $batch->id) }}" class="btn btn-outline-info btn-sm">View Batch</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No production batches associated with this order yet.</p>
                {{-- Option to create a new batch for this order --}}
                {{-- <button class="btn btn-outline-success btn-sm">Create Batch for this Order</button> --}}
            @endif
        </div>
    </div>
</div>
@endsection