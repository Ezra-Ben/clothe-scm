@extends('layouts.app')

@section('title', 'Create Inbound Shipment')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">New Inbound Shipment</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('inbound.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="supplier_order_id" class="form-label">Supplier Order*</label>
                <select class="form-control @error('supplier_order_id') is-invalid @enderror" name="supplier_order_id" required>
                    <option value="">Select Supplier Order</option>
                    @foreach ($supplierOrders as $order)
                        <option value="{{ $order->id }}" @selected(old('supplier_order_id') == $order->id)>
                            Order #{{ $order->id }} - {{ $order->supplier->name }} - 
                            (Expected: {{ $order->expected_delivery_date->format('M d, Y') }})
                        </option>
                    @endforeach
                </select>
                @error('supplier_order_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="carrier_id" class="form-label">Carrier*</label>
                <select class="form-control" name="carrier_id" required>
                    <option value="">Select Carrier</option>
                    @foreach ($carriers as $carrier)
                        <option value="{{ $carrier->id }}">{{ $carrier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="tracking_number" class="form-label">Tracking Number</label>
                <input type="text" class="form-control" name="tracking_number" placeholder="Optional">
            </div>

            <div class="mb-3">
                <label for="estimated_arrival" class="form-label">Estimated Arrival Date</label>
                <input type="datetime-local" name="estimated_arrival" value="{{ old('estimated_arrival', now()->format('Y-m-d\TH:i')) }}" class="form-control">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="processing" selected>Processing</option>
                    <option value="in_transit">In Transit</option>
                    <option value="arrived">Arrived</option>
                    <option value="received">Received</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-truck"></i> Create Shipment
            </button>
        </form>
    </div>
</div>
@endsection
