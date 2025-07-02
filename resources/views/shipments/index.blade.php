@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        <i class="bi bi-truck"></i> {{ __('Shipments') }}
    </h2>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="py-4">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body text-dark">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Order</th>
                                <th>Status</th>
                                <th>Shipped At</th>
                                <th>Delivered At</th>
                                <th>Tracking #</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shipments as $shipment)
                            <tr>
                                <td>{{ $shipment->id }}</td>
                                <td><span class="badge bg-primary">#{{ $shipment->order->id ?? '-' }}</span></td>
                                <td>
                                    <span class="badge {{ $shipment->status === 'Delivered' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $shipment->status }}
                                    </span>
                                </td>
                                <td>{{ $shipment->shipped_at }}</td>
                                <td>{{ $shipment->delivered_at }}</td>
                                <td>{{ $shipment->tracking_number }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="my-3">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0">No shipments yet.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 