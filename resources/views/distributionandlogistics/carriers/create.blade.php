@extends('layouts.app')

@section('title', 'Add Carrier')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Register New Carrier</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('distributionandlogistics.carriers.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Carrier Name*</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="code" class="form-label">Short Code*</label>
                    <input type="text" class="form-control" name="code" maxlength="10" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="contact_phone" class="form-label">Contact Phone*</label>
                <input type="tel" class="form-control" name="contact_phone" required>
            </div>

            <div class="mb-3">
                <label for="supported_service_levels" class="form-label">Supported Service Levels* (comma separated)</label>
                <input type="text" class="form-control" name="supported_service_levels" placeholder="standard, express, overnight" required>
            </div>

            <div class="mb-3">
                <label for="service_areas" class="form-label">Service Areas* (comma separated)</label>
                <input type="text" class="form-control" name="service_areas" placeholder="Nairobi, Kampala, Mombasa" required>
            </div>

            <div class="mb-3">
                <label for="base_rate_usd" class="form-label">Base Rate (USD)*</label>
                <input type="number" step="0.01" min="0" class="form-control" name="base_rate_usd" required>
            </div>

            <div class="mb-3">
                <label for="max_weight_kg" class="form-label">Max Weight (kg)*</label>
                <input type="number" step="0.01" min="0" class="form-control" name="max_weight_kg" required>
            </div>

            <div class="mb-3">
                <label for="tracking_url_template" class="form-label">Tracking URL Template</label>
                <input type="text" class="form-control" name="tracking_url_template" placeholder="https://tracking.example.com/{tracking_number}">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                <label class="form-check-label" for="is_active">Active</label>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Register 
            </button>
        </form>
    </div>
</div>
@endsection
