@extends('layouts.app')

@section('title', 'Edit Carrier Details')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Edit Carrier: {{ $carrier->name }}</h5>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('distributionandlogistics.carriers.update', $carrier) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Carrier Name</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $carrier->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="code" class="form-label">Code</label>
                <input type="text" class="form-control" name="code" id="code" value="{{ old('code', $carrier->code) }}" required>
            </div>

            <div class="mb-3">
                <label for="contact_phone" class="form-label">Contact Phone</label>
                <input type="text" class="form-control" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $carrier->contact_phone) }}" required>
            </div>

            <div class="mb-3">
                <label for="supported_service_levels" class="form-label">Supported Service Levels (comma-separated)</label>
                <input type="text" class="form-control" name="supported_service_levels" id="supported_service_levels"
                    value="{{ old('supported_service_levels', is_string($carrier->supported_service_levels) ? implode(', ', json_decode($carrier->supported_service_levels)) : '') }}">
            </div>

            <div class="mb-3">
                <label for="service_areas" class="form-label">Service Areas (comma-separated)</label>
                <input type="text" class="form-control" name="service_areas" id="service_areas"
                    value="{{ old('service_areas', is_string($carrier->service_areas) ? implode(', ', json_decode($carrier->service_areas)) : '') }}">
            </div>

            <div class="mb-3">
                <label for="base_rate_usd" class="form-label">Base Rate (USD)</label>
                <input type="number" step="0.01" class="form-control" name="base_rate_usd" id="base_rate_usd"
                    value="{{ old('base_rate_usd', $carrier->base_rate_usd) }}">
            </div>

            <div class="mb-3">
                <label for="max_weight_kg" class="form-label">Max Weight (kg)</label>
                <input type="number" step="0.01" class="form-control" name="max_weight_kg" id="max_weight_kg"
                    value="{{ old('max_weight_kg', $carrier->max_weight_kg) }}">
            </div>

            <div class="mb-3">
                <label for="tracking_url_template" class="form-label">Tracking URL Template</label>
                <input type="text" class="form-control" name="tracking_url_template" id="tracking_url_template"
                    value="{{ old('tracking_url_template', $carrier->tracking_url_template) }}">
            </div>

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                    {{ $carrier->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>

            <button type="submit" class="btn btn-primary btn-sm">Apply Changes</button>
            <a href="{{ route('distributionandlogistics.admin.index') }}" class="btn btn-primary btn-sm">Cancel</a>
        </form>
    </div>
</div>
@endsection
