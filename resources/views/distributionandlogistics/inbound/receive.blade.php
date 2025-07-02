@extends('layouts.app')

@section('title', 'Receive Shipment #' . $shipment->id)

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5>Receiving Report for Shipment #{{ $shipment->id }}</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('inbound.receive', $shipment) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="actual_arrival" class="form-label">Actual Arrival Time</label>
                <input type="datetime-local" class="form-control @error('actual_arrival') is-invalid @enderror" 
                       name="actual_arrival" value="{{ old('actual_arrival', now()->format('Y-m-d\TH:i')) }}" required>
                @error('actual_arrival')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="condition" class="form-label">Condition of Shipment</label>
                <input type="text" name="condition" 
                       class="form-control @error('condition') is-invalid @enderror" 
                       placeholder="'excellent', 'good', 'damaged'" required>{{ old('condition') }}
                @error('condition')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="discrepancy_notes" class="form-label">Discrepancy Notes (optional)</label>
                <textarea name="discrepancy_notes" class="form-control @error('discrepancy_notes') is-invalid @enderror" rows="3">{{ old('discrepancy_notes') }}</textarea>
                @error('discrepancy_notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Submit Receiving Report
            </button>
        </form>
    </div>
</div>
@endsection
