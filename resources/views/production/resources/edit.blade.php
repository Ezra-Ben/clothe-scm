@extends('layouts.app') 

@section('content')
<div class="container">
    <h1>Edit Resource: {{ $resource->name }}</h1>

    <form action="{{ route('resources.update', $resource->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Use PUT method for updates --}}

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $resource->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <input type="text" class="form-control" id="type" name="type" value="{{ old('type', $resource->type) }}">
        </div>

        <div class="mb-3">
            <label for="capacity" class="form-label">Capacity (Units/Hr)</label>
            <input type="number" class="form-control" id="capacity" name="capacity" value="{{ old('capacity', $resource->capacity_units_per_hour) }}">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                <option value="in_use" {{ old('status') == 'in_use' ? 'selected' : '' }}>In Use</option>
                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Under Maintenance</option>
                <option value="offline" {{ old('status') == 'offline' ? 'selected' : '' }}>Offline</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Resource</button>
        <a href="{{ route('resources.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection