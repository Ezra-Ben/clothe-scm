@extends('layouts.app')

@section('title', 'Edit Inbound Shipment')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Edit Inbound Shipment: {{ $shipment->tracking_number }}</h5>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('inbound.update', $shipment) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="carrier_id" class="form-label">Carrier</label>
                <select name="carrier_id" class="form-select" required>
                    @foreach($carriers as $carrier)
                        <option value="{{ $carrier->id }}" {{ $shipment->carrier_id == $carrier->id ? 'selected' : '' }}>
                            {{ $carrier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="tracking_number" class="form-label">Tracking Number</label>
                <input type="text" name="tracking_number" class="form-control" value="{{ old('tracking_number', $shipment->tracking_number) }}" required>
            </div>

            <div class="mb-3">
                <label for="estimated_arrival" class="form-label">Estimated Arrival</label>
                <input type="datetime-local" name="estimated_arrival" class="form-control" 
                    value="{{ old('estimated_arrival', \Carbon\Carbon::parse($shipment->estimated_arrival)->format('Y-m-d\TH:i')) }}">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['processing', 'in_transit', 'arrived', 'received'] as $status)
                        <option value="{{ $status }}" {{ $shipment->status == $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Apply Changes</button>
            <a href="{{ route('distributionandlogistics.admin.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
        </form>
    </div>
</div>
@endsection
