 @extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.production') }}" class="text-decoration-none text-primary">Production Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Production Request #{{ $productionOrder->id }}</li>
        </ol>
    </nav>

    <h1 class="mb-4 text-primary">Production Request #{{ $productionOrder->id }}Details</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4 shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Batch Code:</strong> {{ $productionOrder->batch_code }}</p>
                    <p><strong>Product:</strong> {{ $productionOrder->product->name }}</p>
                    <p><strong>Quantity to Produce:</strong> {{ $productionOrder->quantity }}</p>
                    <p><strong>Current Status:</strong> <span class="badge bg-{{ $productionOrder->status === 'Completed' ? 'success' : ($productionOrder->status === 'In Progress' ? 'info' : 'secondary') }}">{{ $productionOrder->status }}</span></p>
                    <p><strong>Urgent:</strong> {{ $productionOrder->urgent ? 'Yes' : 'No' }}</p>
                    <p><strong>Packaging Status:</strong> {{ $productionOrder->packaging_status }}</p>
                    <p><strong>Scheduled At:</strong> {{ $productionOrder->scheduled_at->format('Y-m-d H:i') }}</p>
                    @if ($productionOrder->completed_at)
                        <p><strong>Completed At:</strong> {{ $productionOrder->completed_at->format('Y-m-d H:i') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            @if ($productionOrder->bom)
            <div class="card mb-4 shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Bill of Materials (BOM)</h5>
                </div>
                <div class="card-body">
                    <p><strong>BOM for:</strong> {{ $productionOrder->bom->product->name }}</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>Raw Material</th>
                                    <th>Qty Required</th>
                                    <th>Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productionOrder->bom->bomItems as $bomItem)
                                <tr>
                                    <td>{{ $bomItem->rawMaterial->name }}</td>
                                    <td>{{ $bomItem->quantity }}</td>
                                    <td>{{ $bomItem->rawMaterial->unit }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('boms.show', $productionOrder->bom->id) }}" class="btn btn-sm btn-outline-primary mt-2">View Full BOM</a>
                </div>
            </div>
            @else
            <div class="alert alert-warning shadow" role="alert">
                <i class="fas fa-exclamation-triangle"></i> No Bill of Materials attached to this Production Request.
            </div>
            @endif
        </div>
    </div>

    <div class="card mb-4 shadow">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Linked Customer Order Items</h5>
        </div>
        <div class="card-body">
            @if ($productionOrder->orderItems->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Order Item ID</th>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Fulfilled From</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productionOrder->orderItems as $orderItem)
                            <tr>
                                <td>#{{ $orderItem->id }}</td>
                                <td>
                                    <a href="{{ route('order_requests.show', $orderItem->order->id) }}" class="text-primary text-decoration-none">
                                        #{{ $orderItem->order->id }}
                                    </a>
                                </td>
                                <td>{{ $orderItem->order->customer_name ?? 'N/A' }}</td>
                                <td>{{ $orderItem->product->name }}</td>
                                <td>{{ $orderItem->quantity }}</td>
                                <td>{{ $orderItem->fulfilled_from_stock ? 'Stock' : 'MTO' }}</td>
                                <td><span class="badge bg-{{ $orderItem->status === 'Fulfilled' ? 'success' : 'warning' }}">{{ $orderItem->status }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">This production request is not directly linked to specific customer order items (e.g., it might be for general stock replenishment).</p>
            @endif
        </div>
    </div>

    <div class="text-end mt-4">
        <a href="{{ route('dashboard.production') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>
@endsection
   
