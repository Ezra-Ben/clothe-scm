@extends('layouts.app')

@section('content')
<div class="container py-4">
    <x-breadcrumb :items="[
        ['label' => 'Reports & Analytics', 'url' => route('reports.index')],
        ['label' => 'Product Performance', 'url' => route('reports.product-performance')]
    ]" />
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">
                <i class="fas fa-box me-2"></i>Product Performance Report
            </h1>
            <p class="text-muted mb-0">Detailed analysis of product performance and production metrics</p>
        </div>
        <div class="d-flex gap-2">
            <x-export-buttons :route="route('reports.export')" />
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Analytics
            </a>
        </div>
    </div>

    <!-- Performance Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-box fa-2x text-primary"></i>
                    </div>
                    <h4 class="mb-1">{{ $products->total() }}</h4>
                    <small class="text-muted">Total Products</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-industry fa-2x text-success"></i>
                    </div>
                    <h4 class="mb-1">{{ $products->sum('production_batches_count') }}</h4>
                    <small class="text-muted">Total Production Batches</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-check-circle fa-2x text-info"></i>
                    </div>
                    <h4 class="mb-1">{{ $products->sum('quality_controls_count') }}</h4>
                    <small class="text-muted">Total QC Records</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-chart-line fa-2x text-warning"></i>
                    </div>
                    <h4 class="mb-1">{{ number_format($products->sum('production_batches_sum_quantity') ?? 0) }}</h4>
                    <small class="text-muted">Total Quantity Produced</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Performance Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-table me-2"></i>Product Performance Details
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Stock</th>
                            <th>Price</th>
                            <th>Batches</th>
                            <th>QC Records</th>
                            <th>Total Quantity</th>
                            <th>Performance Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            @php
                                $performanceScore = 0;
                                if ($product->production_batches_count > 0) $performanceScore += 30;
                                if ($product->quality_controls_count > 0) $performanceScore += 20;
                                if ($product->stock > 10) $performanceScore += 25;
                                if ($product->price > 50) $performanceScore += 25;
                                
                                $scoreColor = $performanceScore >= 80 ? 'success' : ($performanceScore >= 60 ? 'warning' : 'danger');
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->description)
                                        <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                    @endif
                                </td>
                                <td><code>{{ $product->sku }}</code></td>
                                <td>
                                    <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td><span class="badge bg-primary">${{ number_format($product->price, 2) }}</span></td>
                                <td>
                                    <span class="badge bg-info">{{ $product->production_batches_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $product->quality_controls_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ number_format($product->production_batches_sum_quantity ?? 0) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 60px; height: 6px;">
                                            <div class="progress-bar bg-{{ $scoreColor }}" style="width: {{ $performanceScore }}%"></div>
                                        </div>
                                        <small class="text-{{ $scoreColor }} fw-bold">{{ $performanceScore }}%</small>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-box fa-2x mb-2"></i>
                                        <p>No products found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif

    <!-- Performance Insights -->
    <div class="row g-4 mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Performance Insights
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-arrow-up text-success me-2"></i>
                            <strong>Top Performers:</strong> Products with high production batches and QC records
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            <strong>Low Stock Alert:</strong> Products with stock levels below 10 units
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-chart-line text-info me-2"></i>
                            <strong>Production Efficiency:</strong> Products with consistent batch production
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <strong>Quality Focus:</strong> Products with regular quality control checks
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Performance Metrics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h5 class="text-primary mb-1">{{ $products->avg('production_batches_count') ? round($products->avg('production_batches_count'), 1) : 0 }}</h5>
                                <small class="text-muted">Avg Batches/Product</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h5 class="text-success mb-1">{{ $products->avg('quality_controls_count') ? round($products->avg('quality_controls_count'), 1) : 0 }}</h5>
                                <small class="text-muted">Avg QC Records/Product</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h5 class="text-info mb-1">{{ $products->avg('stock') ? round($products->avg('stock'), 1) : 0 }}</h5>
                                <small class="text-muted">Avg Stock Level</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h5 class="text-warning mb-1">${{ $products->avg('price') ? round($products->avg('price'), 2) : 0 }}</h5>
                                <small class="text-muted">Avg Price</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 