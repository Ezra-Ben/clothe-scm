@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Create New Carrier</h3>
    <form method="POST" action="{{ route('carriers.store') }}">
        @csrf
        <div class="mb-3">
            <label>Contact Phone</label>
            <input type="text" name="contact_phone" class="form-control">
        </div>
        <div class="mb-3">
            <label>Vehicle Type</label>
            <input type="text" name="vehicle_type" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>License Plate</label>
            <input type="text" name="license_plate" class="form-control">
        </div>
        <div class="mb-3">
            <label>Max Weight (kg)</label>
            <input type="number" name="max_weight_kg" class="form-control">
        </div>
        <div class="mb-3">
            <label>Service Areas</label>
            <input type="text" name="service_areas" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select">
                <option value="free">Free</option>
                <option value="busy">Busy</option>
            </select>
        </div>
        <div class="text-end">
            <button class="btn btn-primary">Create Carrier</button>
        </div>
    </form>
</div>
@endsection
