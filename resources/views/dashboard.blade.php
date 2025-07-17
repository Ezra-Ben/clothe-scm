@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        <i class="fas fa-tachometer-alt me-2"></i>{{ __('Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="container py-4">
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <x-dashboard-widget 
                title="Total Products" 
                value="{{ App\Models\Product::count() }}" 
                icon="box" 
                color="primary"
                description="In inventory" />
        </div>
        <div class="col-md-3">
            <x-dashboard-widget 
                title="Production Batches" 
                value="{{ App\Models\ProductionBatch::count() }}" 
                icon="industry" 
                color="info"
                description="Active batches" />
        </div>
        <div class="col-md-3">
            <x-dashboard-widget 
                title="Quality Controls" 
                value="{{ App\Models\QualityControl::count() }}" 
                icon="check-circle" 
                color="success"
                description="QC records" />
        </div>
        <div class="col-md-3">
            <x-dashboard-widget 
                title="Low Stock Items" 
                value="{{ App\Models\Product::where('stock', '<', 10)->count() }}" 
                icon="exclamation-triangle" 
                color="warning"
                description="Need restocking" />
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('products.create') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-plus me-2"></i>Add Product
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('production-batches.create') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-industry me-2"></i>Create Batch
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('quality-controls.create') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-check-circle me-2"></i>Quality Check
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-list me-2"></i>View All
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Recent Products
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $recentProducts = App\Models\Product::latest()->take(5)->get();
                    @endphp
                    @if($recentProducts->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentProducts as $product)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $product->name }}</strong>
                                        <br><small class="text-muted">SKU: {{ $product->sku }}</small>
                                    </div>
                                    <span class="badge bg-primary">{{ number_format($product->price, 2) }} UGX</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">No products yet</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>System Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Database Connection</span>
                            <span class="badge bg-success">Connected</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Application Status</span>
                            <span class="badge bg-success">Running</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>User Session</span>
                            <span class="badge bg-info">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Last Login</span>
                            <small class="text-muted">{{ auth()->user()->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Actions -->
    @can('manage-suppliers')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning bg-opacity-10">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Admin Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3">
                        <a href="{{ route('admin.select.supplier') }}" class="btn btn-warning">
                            <i class="fas fa-users me-2"></i>Manage Suppliers
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-box me-2"></i>View Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info bg-opacity-10">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>User Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3">
                        @if(!auth()->user()->vendor || !auth()->user()->vendor->supplier)
                            <!-- Removed 'Apply as Vendor' button as requested -->
                        @endif
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-box me-2"></i>Browse Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</div>
@endsection
