@extends('layouts.app')

@section('content')
<h1 class="mb-4 text-primary">Production Summary Report</h1>

<div class="card mb-4 shadow-sm border-primary">
    <div class="card-header bg-primary text-white">
        Filter Report
    </div>
    <div class="card-body">
        <form action="{{ route('reports.production_summary') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate ?? '' }}">
            </div>
            <div class="col-md-3">
                <label for="status" class="form-label">Order Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">All</option>
                    <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ ($status ?? '') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ ($status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ ($status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="product" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="product" name="product" value="{{ $product ?? '' }}" placeholder="e.g.,Cotton Shirt">
            </div>
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('reports.production_summary') }}" class="btn btn-outline-secondary">Reset Filters</a>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <p class="card-text fs-2">{{ $totalOrders }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success h-100 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Completed Orders</h5>
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
                <h5 class="card-title">On-Time Delivery Rate</h5>
                <p class="card-text fs-2">{{ $onTimeRate }}%</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-primary">
    <div class="card-header bg-primary text-white">
        Detailed Order List
    </div>
    <div class="card-body">
        @if($orders->isEmpty())
            <p class="text-center">No orders found for the selected filters.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Actual End Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->product->name ?? 'N/A' }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td><span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'in_progress' ? 'info' : 'secondary') }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></td>
                                <td>{{ $order->start_date }}</td>
                                <td>{{ $order->end_date }}</td>
                                <td>{{ $order->actual_end_date ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- For Chart.js integration (optional for visual graphs) --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Example: Render a simple chart for order status distribution
    const ctx = document.getElementById('orderStatusChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Completed', 'In Progress', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [{{ $orders->where('status', 'completed')->count() }}, {{ $orders->where('status', 'in_progress')->count() }}, {{ $orders->where('status', 'pending')->count() }}, {{ $orders->where('status', 'cancelled')->count() }}],
                    backgroundColor: ['#198754', '#0dcaf0', '#6c757d', '#dc3545'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Order Status Distribution'
                    }
                }
            }
        });
    }
</script>
@endpush
@endsection