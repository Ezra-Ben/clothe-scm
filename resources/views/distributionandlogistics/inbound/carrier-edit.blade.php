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

            <!-- Carrier - readonly -->
            <div class="mb-3">
                <label class="form-label">Carrier</label>
                <input type="text" class="form-control" value="{{ $shipment->carrier->name }}" readonly>
            </div>

            <!-- Tracking Number - readonly -->
            <div class="mb-3">
                <label class="form-label">Tracking Number</label>
                <input type="text" class="form-control" value="{{ $shipment->tracking_number }}" readonly>
            </div>

            <!-- Estimated Arrival - editable -->
            <div class="mb-3">
                <label class="form-label">Estimated Arrival</label>
                <input type="datetime-local" name="estimated_arrival" class="form-control" 
                    value="{{ old('estimated_arrival', \Carbon\Carbon::parse($shipment->estimated_arrival)->format('Y-m-d\TH:i')) }}">
            </div>

            <!-- Status - editable -->
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['processing', 'in_transit', 'arrived', 'received'] as $status)
                        <option value="{{ $status }}" {{ $shipment->status == $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Submit & Cancel -->
            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">Apply Changes</button>
                <a href="{{ route('distributionandlogistics.admin.index') }}" class="btn btn-outline-secondary btn-sm">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
