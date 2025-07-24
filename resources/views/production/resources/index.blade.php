@extends('layouts.app')

@section('content')
<h1 class="mb-4 text-primary">Manage Resources</h1>

<div class="d-flex align-items-center mb-3">
    <a href="{{ route('resources.create') }}" class="btn btn-primary me-auto">Add New Resource</a>
    <a href="{{ route('capacity_planning.index') }}" class="btn btn-outline-primary me-2">Capacity Planning</a>
    <a href="{{ route('reports.resource_utilization') }}" class="btn btn-outline-secondary">Utilization Report</a>
</div>


@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-primary">
    <div class="card-header bg-primary text-white">
        Available Resources
    </div>
    <div class="card-body">
        @if($resources->isEmpty())
            <p class="text-center">No resources defined yet. Please add some.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Capacity (Units/Hr)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resources as $resource)
                            <tr>
                                <td>{{ $resource->name }}</td>
                                <td>{{ ucfirst($resource->type) }}</td>
                                <td>{{ $resource->capacity_units_per_hour ?? 'N/A' }}</td>
                                <td><span class="badge bg-{{ $resource->status == 'available' ? 'success' : ($resource->status == 'in_use' ? 'info' : ($resource->status == 'maintenance' ? 'warning' : 'secondary')) }}">{{ ucfirst(str_replace('_', ' ', $resource->status)) }}</span></td>
                                <td>
                                    <a href="{{ route('resources.edit', $resource->id) }}" class="btn btn-sm btn-outline-primary me-2">Edit</a>
                                    <form action="{{ route('resources.destroy', $resource->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this resource?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection