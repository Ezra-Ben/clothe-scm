@extends('layouts.app')

@section('title', 'Change Delivery Address')

@section('content')
<div class="col-md-8 mx-auto">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Change Delivery Address – Tracking #{{ $delivery->tracking_number }}</h5>
        </div>

        <div class="card-body">
            <p><strong>Old Address:</strong> {{ $currentAddress }}</p>
            <form method="POST" action="{{ route('distributionandlogistics.deliveries.change-address.update', $delivery) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="new_address" class="form-label">Change Delivery Location to...</label>
                    <input type="text" class="form-control @error('new_address') is-invalid @enderror" 
                           name="new_address" id="new_address" 
                           value="{{ old('new_address') }}" required>

                    @error('new_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('distributionandlogistics.users.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-geo-alt"></i> Update Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
