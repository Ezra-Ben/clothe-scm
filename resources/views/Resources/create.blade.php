@extends('layouts.app')

@section('content')
<h1 class="mb-4 text-primary">Add New Resource</h1>

<div class="card shadow-sm border-primary">
    <div class="card-header bg-primary text-white">
        Resource Details
    </div>
    <div class="card-body">
        <form action="{{ route('resources.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Resource Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                    <option value="">Select Type</option>
                    <option value="machine" {{ old('type') == 'machine' ? 'selected' : '' }}>Machine</option>
                    <option value="labor" {{ old('type') == 'labor' ? 'selected' : '' }}>Labor</option>
                    <option value="workstation" {{ old('type') == 'workstation' ? 'selected' : '' }}>Workstation</option>
                    <option value="facility" {{ old('type') == 'facility' ? 'selected' : '' }}>Facility</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="capacity_units_per_hour" class="form-label">Capacity (Units/Hour - Optional)</label>
                <input type="number" step="0.01" class="form-control @error('capacity_units_per_hour') is-invalid @enderror" id="capacity_units_per_hour" name="capacity_units_per_hour" value="{{ old('capacity_units_per_hour') }}">
                @error('capacity_units_per_hour')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="in_use" {{ old('status') == 'in_use' ? 'selected' : '' }}>In Use</option>
                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                    <option value="offline" {{ old('status') == 'offline' ? 'selected' : '' }}>Offline</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Add Resource</button>
            <a href="{{ route('resources.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection