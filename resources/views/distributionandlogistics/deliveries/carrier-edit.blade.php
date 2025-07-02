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

            <!-- Carrier - readonly -->
            <div class="mb-3">
                <label class="form-label">Carrier</label>
                <input type="text" class="form-control" value="{{ $delivery->carrier->name }}" readonly>
            </div>

            <!-- Tracking Number - readonly -->
            <div class="mb-3">
                <label class="form-label">Tracking Number</label>
                <input type="text" class="form-control" value="{{ $delivery->tracking_number }}" readonly>
            </div>

            <!-- Service Level - readonly -->
            <div class="mb-3">
                <label class="form-label">Service Level</label>
                <input type="text" class="form-control" value="{{ $delivery->service_level }}" readonly>
            </div>

            <!-- Estimated Delivery - editable -->
            <div class="mb-3">
                <label class="form-label">Estimated Delivery</label>
                <input type="datetime-local" name="estimated_delivery" class="form-control"
                    value="{{ old('estimated_delivery', optional($delivery->estimated_delivery)->format('Y-m-d\TH:i')) }}">
            </div>

            <!-- Status - editable -->
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['pending', 'processing', 'dispatched', 'in_transit', 'out_for_delivery', 'delivered', 'failed'] as $status)
                        <option value="{{ $status }}" {{ $delivery->status == $status ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Notes - editable -->
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $delivery->notes) }}</textarea>
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
