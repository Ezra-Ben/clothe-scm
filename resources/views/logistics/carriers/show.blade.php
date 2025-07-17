@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Carrier Details</h3>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>{{ $carrier->user->name ?? 'N/A' }}</h5>
            <a href="{{ route('logistics.carriers.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Vehicle Type:</strong> {{ $carrier->vehicle_type }}
                </div>
                <div class="col-md-6">
                    <strong>License Plate:</strong> {{ $carrier->license_plate }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Service Areas:</strong> {{ $carrier->service_areas }}
                </div>
                <div class="col-md-6">
                    <strong>Status:</strong>
                    <span class="badge bg-{{ $carrier->status == 'free' ? 'success' : 'warning' }}">
                        {{ ucfirst($carrier->status) }}
                    </span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Max Weight (kg):</strong> {{ $carrier->max_weight_kg }}
                </div>
                <div class="col-md-6">
                    <strong>Contact Phone:</strong> {{ $carrier->contact_phone }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Customer Rating:</strong> {{ $carrier->customer_rating ?? 'N/A' }}
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('carriers.destroy', $carrier->id) }}"
            onsubmit="return confirm('Are you sure you want to delete this carrier?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Delete Carrier</button>
        </form>

    </div>
</div>
@endsection
