@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Carrier</h3>

    <form method="POST" action="{{ route('carriers.update', $carrier->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Carrier Name</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="{{ old('name', $carrier->user->name) }}" disabled>
        </div>

        <div class="mb-3">
            <label for="contact_phone" class="form-label">Contact Phone</label>
            <input type="text" id="contact_phone" name="contact_phone" class="form-control"
                    value="{{ old('contact_phone', $carrier->contact_phone) }}">
        </div>

        <div class="mb-3">
            <label for="vehicle_type" class="form-label">Vehicle Type</label>
            <input type="text" id="vehicle_type" name="vehicle_type" class="form-control"
                   value="{{ old('vehicle_type', $carrier->vehicle_type) }}" required>
        </div>

        <div class="mb-3">
            <label for="license_plate" class="form-label">License Plate</label>
            <input type="text" id="license_plate" name="license_plate" class="form-control"
                    value="{{ old('license_plate', $carrier->license_plate) }}">
        </div>

        <div class="mb-3">
            <label for="max_weight_kg" class="form-label">Max Weight (kg)</label>
            <input type="number" id="max_weight_kg" name="max_weight_kg" class="form-control"
                    value="{{ old('max_weight_kg', $carrier->max_weight_kg) }}">
        </div>

        <div class="mb-3">
            <label for="service_areas" class="form-label">Service Areas</label>
            <input type="text" id="service_areas" name="service_areas" class="form-control"
                   value="{{ old('service_areas', $carrier->service_areas) }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select id="status" name="status" class="form-select" required>
                <option value="free" {{ old('status', $carrier->status) === 'free' ? 'selected' : '' }}>Free</option>
                <option value="busy" {{ old('status', $carrier->status) === 'busy' ? 'selected' : '' }}>Busy</option>
            </select>
        </div>

        <div class="text-end">
            <button class="btn btn-primary">Update Carrier</button>
        </div>
    </form>
</div>
@endsection
