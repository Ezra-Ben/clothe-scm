@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-primary">Production Reports & Analytics</h1>

    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card bg-light shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Stock vs Make-to-Order Percentage</h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="mb-0 text-success">{{ round($stockPercentage, 2) }}% Stock</h2>
                    <h2 class="mb-0 text-info">{{ round($mtoPercentage, 2) }}% Make-to-Order</h2>
                    <p class="text-muted mt-2">Based on all processed order items.</p>
                    <div class="progress mt-3" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stockPercentage }}%;" aria-valuenow="{{ $stockPercentage }}" aria-valuemin="0" aria-valuemax="100">Stock</div>
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $mtoPercentage }}%;" aria-valuenow="{{ $mtoPercentage }}" aria-valuemin="0" aria-valuemax="100">MTO</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Average Production Lead Time</h5>
                </div>
                <div class="card-body text-center">
                    <h2 class="mb-0 text-primary">{{ $averageLeadTimeHours }} Hours</h2>
                    <p class="text-muted mt-2">Average time from start to completion for production orders.</p>
                    {{-- You could add a graph here if using a charting library --}}
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Raw Material Usage (for Completed Production)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Raw Material</th>
                            <th>Total Quantity Used</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rawMaterialUsage as $usage)
                        <tr>
                            <td><strong>{{ $usage->raw_material_name }}</strong></td>
                            <td>{{ round($usage->total_used, 2) }}</td>
                            <td>{{ $usage->unit }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">No raw material usage data available for completed production.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
