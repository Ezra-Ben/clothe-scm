@extends('layouts.app')

@section('content')
<div class="container-fluid py-4"> 
    {{-- Dashboard Title --}}
    <h1 class="text-primary mb-4">Production Dashboard</h1>

    {{-- Navigation Buttons --}}
    <div class="d-flex justify-content-start mb-4 flex-wrap"> 
        <a href="{{ route('order_requests.index') }}" class="btn btn-outline-info me-2 mb-2">
            <i class="fas fa-receipt me-1"></i> Pending Orders
        </a>
        <a href="{{ route('boms.index') }}" class="btn btn-outline-warning me-2 mb-2">
            <i class="fas fa-list-alt me-1"></i> BOM Management
        </a>
        <a href="{{ route('reports.production') }}" class="btn btn-outline-success me-2 mb-2">  
            <i class="fas fa-chart-bar me-1"></i> Production Reports
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error')) 
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        {{-- Metric Cards --}}
        <div class="col-lg-8">
            <h4 class="text-primary mb-3"><i class="fas fa-industry me-2"></i> Production Batches Summary</h4>
            <div class="row">
                <div class="col-sm-6 col-md-3 mb-3">
                    <div class="card bg-primary text-white text-center shadow">
                        <div class="card-body">
                            <h5 class="card-title">Total Batches</h5>
                            <p class="card-text fs-2">{{ $totalBatches ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-3">
                    <div class="card bg-warning text-dark text-center shadow">
                        <div class="card-body">
                            <h5 class="card-title">Pending</h5>
                            <p class="card-text fs-2">{{ $pendingBatches ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-3">
                    <div class="card bg-info text-white text-center shadow">
                        <div class="card-body">
                            <h5 class="card-title">In Progress</h5>
                            <p class="card-text fs-2">{{ $inProgressBatches ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-3">
                    <div class="card bg-success text-white text-center shadow">
                        <div class="card-body">
                            <h5 class="card-title">Completed</h5>
                            <p class="card-text fs-2">{{ $completedBatches ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="text-primary mb-3">Filter Batches</h5>
                    <form method="GET" action="{{ route('dashboard.production') }}">
                        <div class="mb-3">
                            <input type="text" name="search" class="form-control" placeholder="Search by batch code" value="{{ request('search') }}">
                        </div>
                        <div class="mb-3">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <select name="product" class="form-select">
                                <option value="">All Products</option>
                                @foreach(\App\Models\Product::all() as $product)
                                    <option value="{{ $product->id }}" {{ request('product') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        {{--Submit Button--}}
                        <div class="d-grid"> 
                            <button type="submit" class ="btn btn-primary">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Production Batches Table --}}
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Production Batches List</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle"> 
                    <thead class="table-light"> 
                        <tr>
                            <th>Batch Code</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Urgent</th>
                            <th>Packaging</th>
                            <th>Scheduled At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productionOrders as $batch) 
                        <tr>
                            <td>
                                {{-- Link to Production Order Detail View --}}
                               @if (isset($batch['id']))
                                <a href="{{ route('production-orders.show', $batch['id']) }}" class="text-primary text-decoration-none">
                                   <strong>{{ $batch['batch_code']}}</strong>
                                </a>
                                @else
                                <strong>{{ $batch['batch_code']}}</strong>
                                  @endif
                            </td>
                            <td>{{ $batch->product->name ?? 'N/A' }}</td> 
                            <td>{{ $batch->quantity }}</td>
                            <td>
                                @php
                                    $statusMap = [
                                        'Completed' => ['label' => 'Completed', 'class' => 'success', 'icon' => 'check-circle'],
                                        'Pending' => ['label' => 'Pending', 'class' => 'warning text-dark', 'icon' => 'hourglass-half'],
                                        'In Progress' => ['label' => 'In Progress', 'class' => 'info text-dark', 'icon' => 'sync-alt']
                                    ];
                                    $map = $statusMap[$batch->status] ?? ['label' => 'Unknown', 'class' => 'secondary', 'icon' => 'question'];
                                @endphp
                                <span class="badge bg-{{ $map['class'] }} px-3 py-2 rounded-pill">
                                    {{ $map['label'] }} <i class="fas fa-{{ $map['icon'] }} ms-1"></i>
                                </span>
                            </td>
                            <td>{{ $batch->urgent ? 'Yes 🔥' : 'No' }}</td> 
                            <td>{{ ucfirst($batch->packaging_status) }}</td>
                            <td>{{ $batch->scheduled_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">  
                                    {{-- Edit Button --}}
                                    <a href="{{ route('production-orders.edit', $batch->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('production-orders.destroy', $batch->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this production order? This action cannot be undone.')">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>

                                    {{-- Complete Button (Conditional) --}}
                                    @if($batch->status !== 'Completed') 
                                        <form action="{{ route('production_orders.complete', $batch->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT') {{-- Assuming your complete route is PUT --}}
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to mark this production order as completed?')">
                                                <i class="fas fa-check"></i> Complete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">Completed</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No production batches found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $productionOrders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

