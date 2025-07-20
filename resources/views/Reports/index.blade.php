@extends('layouts.app')

@section('content')
<h1 class="mb-4 text-primary">Production Reports & Analytics</h1>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Production Summary</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Overall view of production performance, including completed orders, quantities, and on-time delivery rates.</p>
                <a href="{{ route('reports.production_summary') }}" class="btn btn-outline-primary">View Report</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Resource Utilization</h5>
            </div>
            <div class="card-body">
                <p class="card-text">View how machines and labor resources are being utilized.</p>
                <a href="{{ route('reports.resource_utilization') }}" class="btn btn-outline-primary">View Report</a>
            </div>
        </div>
    </div>
</div>
@endsection