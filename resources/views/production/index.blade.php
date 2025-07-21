@extends('layouts.app')

@section('header')
    <h2 class="fw-semibold text-center text-primary mb-1">Production Dashboard</h2>
@endsection

@section('content')
<div class="container">

    {{-- Notifications --}}
    @foreach(auth()->user()->unreadNotifications as $notification)
        <div class="alert alert-info d-flex justify-content-between align-items-center">
            <span>{{ $notification->data['message'] }}</span>
            <a href="{{ $notification->data['url'] }}" class="btn btn-primary btn-sm">View</a>
            <form method="POST" action="{{ route('notifications.markRead', $notification->id) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-secondary btn-sm ms-2">Mark as Read</button>
            </form>
        </div>
    @endforeach

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Production Batches</h5>
                    <p class="card-text fs-1">{{ $totalBatches }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Completed Batches</h5>
                    <p class="card-text fs-1">{{ $completedBatches }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-warning shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Pending Batches</h5>
                    <p class="card-text fs-1">{{ $pendingBatches }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Access Buttons --}}
    <div class="d-flex justify-content-end mb-3 gap-2">
        <a href="{{ route('boms.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-diagram-3"></i> Manage BOM
        </a>
        <a href="{{ route('production.report') }}" class="btn btn-outline-primary">
            <i class="bi bi-clipboard-data"></i> Production Report
        </a>
    </div>

    {{-- QC Bar Summary Card --}}
    <div class="card mb-4 shadow-sm border-primary">
        <div class="card-header bg-primary text-white">Quality Control Status Overview</div>
        <div class="card-body">
            <div class="d-flex align-items-center mb-2">
                <span class="me-2">Passed ({{ $qcPassed }})</span>
                <div class="progress flex-grow-1 me-2" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $qcPassedPercent }}%;"></div>
                </div>
                <span>{{ round($qcPassedPercent,1) }}%</span>
            </div>
            <div class="d-flex align-items-center">
                <span class="me-2">Failed ({{ $qcFailed }})</span>
                <div class="progress flex-grow-1 me-2" style="height: 20px;">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $qcFailedPercent }}%;"></div>
                </div>
                <span>{{ round($qcFailedPercent,1) }}%</span>
            </div>
        </div>
    </div>

    {{-- Table: Production Batches --}}
    <div class="card mb-4 shadow-sm border-primary">
        <div class="card-header bg-primary text-white">Recent Production Batches</div>
        <div class="card-body table-responsive">
            @if($batches->isEmpty())
                <p class="text-center mb-0">No recent production batches available.</p>
            @else
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Batch #</th>
                            <th>P-Order ID</th>
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
                                <td>Batch{{ $batch->id }}</td>
                                <td>Order{{ $batch->production_order_id }}</td>
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
            @endif
        </div>
    </div>

    {{-- Table: Production Orders --}}
    <div class="card shadow-sm border-primary">
        <div class="card-header bg-primary text-white">Recent Production Orders</div>
        <div class="card-body table-responsive">
            @if($orders->isEmpty())
                <p class="text-center mb-0">No recent production orders available.</p>
            @else
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Order Ref</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->order_id ? 'Order'.$order->order_id : 'Restock' }}</td>
                                <td>{{ $order->product->name }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'secondary' : 'info') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('production_orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        Open
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>
@endsection
