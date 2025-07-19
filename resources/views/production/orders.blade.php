@extends('layouts.app')

@section('title', 'Production Orders')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-primary">Production Orders</h1>

    <div class="card shadow-sm bg-white">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span>List of Orders in Production</span>
            <input type="text" id="searchInput" class="form-control form-control-sm w-25" placeholder="Search orders...">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead class="bg-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->customer->name ?? 'N/A' }}</td>
                            <td>{{ $order->product->name ?? 'N/A' }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td><span class="badge {{ $order->status === 'in_production' ? 'bg-primary' : ($order->status === 'completed' ? 'bg-success' : 'bg-warning') }}">{{ Str::title(str_replace('_', ' ', $order->status)) }}</span></td>
                            <td>{{ $order->production_start_date ? $order->production_start_date->format('Y-m-d') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('production.orders.show', $order->id) }}" class="btn btn-outline-info btn-sm">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No orders currently in production.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const ordersTableBody = document.getElementById('ordersTableBody');

        searchInput.addEventListener('keyup', function() {
            const searchTerm = searchInput.value.toLowerCase();
            const rows = ordersTableBody.getElementsByTagName('tr');

            Array.from(rows).forEach(row => {
                const cells = row.getElementsByTagName('td');
                let found = false;
                for (let i = 0; i < cells.length; i++) {
                    const cellText = cells[i].textContent.toLowerCase();
                    if (cellText.includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }
                row.style.display = found ? '' : 'none';
            });
        });
    });
</script>
@endpush