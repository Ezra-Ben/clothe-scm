@extends('layouts.app')

@section('title', 'Edit Delivery')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Edit Delivery: {{ $delivery->tracking_number }}</h5>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('distributionandlogistics.deliveries.update', $delivery) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="carrier_id" class="form-label">Carrier</label>
                <select name="carrier_id" id="carrier_id" class="form-select">
                    @foreach($carriers as $carrier)
                        <option value="{{ $carrier->id }}" {{ $delivery->carrier_id == $carrier->id ? 'selected' : '' }}>
                            {{ $carrier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="tracking_number" class="form-label">Tracking Number</label>
                <input type="text" name="tracking_number" id="tracking_number" class="form-control"
                    value="{{ old('tracking_number', $delivery->tracking_number) }}" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select">
                    @foreach(['pending', 'processing', 'dispatched', 'in_transit', 'out_for_delivery', 'delivered', 'failed'] as $status)
                        <option value="{{ $status }}" {{ $delivery->status == $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="service_level" class="form-label">Service Level</label>
                <input type="text" name="service_level" id="service_level" class="form-control"
                    value="{{ old('service_level', $delivery->service_level) }}">
            </div>

            <div class="mb-3">
                <label for="estimated_delivery" class="form-label">Estimated Delivery</label>
                <input type="datetime-local" name="estimated_delivery" id="estimated_delivery" class="form-control"
                    value="{{ old('estimated_delivery', $delivery->estimated_delivery ? \Carbon\Carbon::parse($delivery->estimated_delivery)->format('Y-m-d\TH:i') : '') }}">
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $delivery->notes) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Apply Changes</button>
            <a href="{{ route('distributionandlogistics.admin.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
        </form>
    </div>
</div>
@endsection
