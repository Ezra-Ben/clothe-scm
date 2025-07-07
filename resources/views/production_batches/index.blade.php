@extends('layouts.app')

@section('content')
<div class="container py-4">
    <x-breadcrumb :items="[
        ['label' => 'Production Batches', 'url' => route('production-batches.index')]
    ]" />
    
    <x-form-success />
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">
                <i class="fas fa-industry me-2"></i>Production Batches
            </h1>
            <p class="text-muted mb-0">Manage production batches and track manufacturing progress</p>
        </div>
        <div>
            <a href="{{ route('production-batches.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create Batch
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('production-batches.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Batches</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by batch number or product...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="status_filter" class="form-label">Status</label>
                    <select class="form-select" id="status_filter" name="status_filter">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ request('status_filter') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status_filter') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status_filter') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="quantity_filter" class="form-label">Quantity Range</label>
                    <select class="form-select" id="quantity_filter" name="quantity_filter">
                        <option value="">All Quantities</option>
                        <option value="small" {{ request('quantity_filter') == 'small' ? 'selected' : '' }}>Small (<100)</option>
                        <option value="medium" {{ request('quantity_filter') == 'medium' ? 'selected' : '' }}>Medium (100-500)</option>
                        <option value="large" {{ request('quantity_filter') == 'large' ? 'selected' : '' }}>Large (>500)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
            @if(request('search') || request('status_filter') || request('quantity_filter'))
                <div class="mt-3">
                    <a href="{{ route('production-batches.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i>Clear Filters
                    </a>
                    <small class="text-muted ms-2">
                        Showing {{ $productionBatches->count() }} of {{ $productionBatches->total() }} batches
                    </small>
                </div>
            @endif
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Batch Number</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productionBatches as $batch)
                            <tr>
                                <td>{{ $batch->id }}</td>
                                <td><code>{{ $batch->batch_number }}</code></td>
                                <td>
                                    <strong>{{ $batch->product->name ?? 'N/A' }}</strong>
                                    @if($batch->product)
                                        <br><small class="text-muted">SKU: {{ $batch->product->sku }}</small>
                                    @endif
                                </td>
                                <td><span class="badge bg-info">{{ $batch->quantity }}</span></td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-warning',
                                            'in_progress' => 'bg-info',
                                            'completed' => 'bg-success',
                                            'cancelled' => 'bg-danger'
                                        ];
                                        $statusColor = $statusColors[$batch->status] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $batch->status)) }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('production-batches.show', $batch) }}" class="btn btn-outline-primary btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('production-batches.edit', $batch) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('production-batches.destroy', $batch) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this batch?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-industry fa-2x mb-2"></i>
                                        <p>No production batches found.</p>
                                        <a href="{{ route('production-batches.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i>Create your first batch
                                        </a>
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
    @if($productionBatches->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $productionBatches->appends(request()->query())->links() }}
        </div>
    @endif
    
    <!-- Navigation buttons at the bottom -->
    <div class="mt-4 text-center">
        <div class="btn-group" role="group">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-box me-1"></i>Products
            </a>
            <a href="{{ route('quality-controls.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-check-circle me-1"></i>Quality Controls
            </a>
        </div>
    </div>
</div>
@endsection 