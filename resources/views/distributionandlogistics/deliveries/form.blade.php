@extends('layouts.app')

@section('title', $delivery->exists ? 'Edit Delivery' : 'Create Delivery')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">{{ $delivery->exists ? 'Edit' : 'New' }} Delivery</h5>
    </div>

    <div class="card-body">
        <form
            action="{{ $delivery->exists
                ? route('distributionandlogistics.deliveries.update', $delivery)
                : route('distributionandlogistics.deliveries.store') }}"
            method="POST">
            @csrf
            @if($delivery->exists)
                @method('PUT')
            @endif

            <!-- Order -->
            <div class="mb-3">
                <label for="order_id" class="form-label">Order</label>
                <select id="order_id" class="form-select" name="order_id" required>
                    <option value="">Select Order</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id }}" {{ $delivery->order_id == $order->id ? 'selected' : '' }}>
                            Order #{{ $order->id }} - {{ $order->customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Carrier -->
            <div class="mb-3">
                <label for="carrier_id" class="form-label">Carrier</label>
                <select id="carrier_id" class="form-select" name="carrier_id" required>
                    <option value="">Select Carrier</option>
                    @foreach($carriers as $carrier)
                        <option value="{{ $carrier->id }}" {{ $delivery->carrier_id == $carrier->id ? 'selected' : '' }}>
                            {{ $carrier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Service Level -->
            <div class="mb-3">
                <label for="service_level" class="form-label">Service Level</label>
                <input type="text" id="service_level" class="form-control" name="service_level"
                    value="{{ old('service_level', $delivery->service_level) }}" required>
            </div>

            <!-- Tracking Number -->
            <div class="mb-3">
                <label for="tracking_number" class="form-label">Tracking Number</label>
                <input type="text" id="tracking_number" class="form-control" name="tracking_number"
                    value="{{ old('tracking_number', $delivery->tracking_number) }}" required>
            </div>

            <!-- Status -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select">
                    @foreach(['pending', 'processing', 'dispatched', 'in_transit', 'out_for_delivery', 'delivered', 'failed'] as $status)
                        <option value="{{ $status }}"
                            {{ (old('status', $delivery->status ?? 'pending') == $status) ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Estimated Delivery -->
            <div class="mb-3">
                <label for="estimated_delivery" class="form-label">Estimated Delivery</label>
                <input type="datetime-local" id="estimated_delivery" class="form-control" name="estimated_delivery"
                    value="{{ old('estimated_delivery', $delivery->estimated_delivery ? \Carbon\Carbon::parse($delivery->estimated_delivery)->format('Y-m-d\TH:i') : '') }}">
            </div>

            <!-- Notes -->
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea id="notes" class="form-control" name="notes" rows="3">{{ old('notes', $delivery->notes) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> {{ $delivery->exists ? 'Update' : 'Create' }} Delivery
            </button>
        </form>
    </div>
</div>
@endsection
