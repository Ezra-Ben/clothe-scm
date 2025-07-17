@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Carriers Overview</h3>

    <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
        @php
            $freeCount = $carriers->where('status', 'free')->count();
            $busyCount = $carriers->where('status', 'busy')->count();
        @endphp

        <div class="col">
            <div class="card border-success shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-truck display-4 text-success mb-2"></i>
                    <h4>{{ $freeCount }}</h4>
                    <p class="mb-0">Available Carriers</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-warning shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-briefcase display-4 text-warning mb-2"></i>
                    <h4>{{ $busyCount }}</h4>
                    <p class="mb-0">Busy Carriers</p>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-primary shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-list-check display-4 text-primary mb-2"></i>
                    <h4>{{ $carriers->count() }}</h4>
                    <p class="mb-0">Total Carriers</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Carrier List</h5>
            <input type="text" id="carrierSearch" class="form-control w-auto" placeholder="Search by name or service area" onkeyup="filterCarriers()">
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0" id="carrierTable">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Vehicle Type</th>
                        <th>Service Areas</th>
                        <th>Status</th>
                        <th>Max Weight (kg)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carriers as $carrier)
                    <tr>
                        <td>{{ $carrier->user->name ?? 'N/A' }}</td>
                        <td>{{ $carrier->vehicle_type }}</td>
                        <td>{{ $carrier->service_areas }}</td>
                        <td>
                            <span class="badge bg-{{ $carrier->status == 'free' ? 'success' : 'warning' }}">
                                {{ ucfirst($carrier->status) }}
                            </span>
                        </td>
                        <td>{{ $carrier->max_weight_kg }}</td>
                        <td>
                            <a href="{{ route('logistics.carriers.show', $carrier->id) }}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
