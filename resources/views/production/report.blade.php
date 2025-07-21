@extends('layouts.app')

@section('content')
<h1 class="mb-4 text-primary">Production Summary Report</h1>

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Total Production Orders</h5>
                <p class="card-text fs-2">{{ $totalOrders }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Completed Production Orders</h5>
                <p class="card-text fs-2">{{ $completedOrders }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-info h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Total Quantity Produced</h5>
                <p class="card-text fs-2">{{ $totalQuantityProduced }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Quality Control Index - Passed (%)</h5>
                <p class="card-text fs-2">{{ $qcPassedPercent }}%</p>
            </div>
        </div>
    </div>
</div>

{{-- Filter Form --}}
<div class="card mb-4 shadow-sm border-primary">
    <div class="card-header bg-primary text-white">Filter Report</div>
    <div class="card-body">
        <form action="{{ route('production.report') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="product" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product" name="product" value="{{ request('product') }}" placeholder="e.g. Cotton Shirt">
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('production.report') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Production Orders Table --}}
<div class="card mb-4 shadow-sm border-primary">
    <div class="card-header bg-primary text-white">Filtered Production Orders</div>
    <div class="card-body">
        @if($orders->isEmpty())
            <p class="text-center">No production orders match your filters.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>P-Order ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->product->name ?? 'N/A' }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'secondary' : 'info') }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('production_orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        Open
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Production Batches Table --}}
<div class="card shadow-sm border-primary">
    <div class="card-header bg-primary text-white">Filtered Production Batches</div>
    <div class="card-body">
        @if($batches->isEmpty())
            <p class="text-center">No production batches match your filters.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Batch ID</th>
                            <th>P-Order ID</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Status</th>
                            <th>Started At</th>
                            <th>Completed At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batches as $batch)
                            <tr>
                                <td>{{ $batch->id }}</td>
                                <td>{{ $batch->production_order_id }}</td>
                                <td>{{ $batch->productionOrder->product->name ?? 'N/A' }}</td>
                                <td>{{ $batch->produced_quantity }}</td>
                                <td>
                                    <span class="badge bg-{{ $batch->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($batch->status) }}
                                    </span>
                                </td>
                                <td>{{ $batch->started_at->format('Y-m-d') }}</td>
                                <td>{{ $batch->completed_at ? $batch->completed_at->format('Y-m-d') : 'N/A' }}</td>
                                <td>
                                    @if($batch->status !== 'completed')
                                        <a href="{{ route('production_batches.edit', $batch->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    @else
                                        <a href="{{ route('quality_control.show', $batch->qualityControl->id ?? 0) }}" class="btn btn-sm btn-outline-primary">
                                            QC Report
                                        </a>
                                    @endif
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
