@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row mb-4">
        {{-- Card: Total Batches --}}
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Production Batches</h5>
                    <p class="card-text fs-1">{{ $totalBatches }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Completed Batches --}}
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Completed Batches</h5>
                    <p class="card-text fs-1">{{ $completedBatches }}</p>
                </div>
            </div>
        </div>

        {{-- Card: Pending Batches --}}
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Pending Batches</h5>
                    <p class="card-text fs-1">{{ $pendingBatches }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- QC Bar Summary --}}
    <div class="mb-4">
        <h5>QC Status Overview</h5>
        <div class="d-flex align-items-center">
            <span class="me-2">Passed ({{ $qcPassed }})</span>
            <div class="progress flex-grow-1 me-2" style="height: 20px;">
                <div class="progress-bar bg-success" role="progressbar"
                    style="width: {{ $qcPassedPercent }}%;"></div>
            </div>
            <span>{{ round($qcPassedPercent,1) }}%</span>
        </div>
        <div class="d-flex align-items-center mt-2">
            <span class="me-2">Failed ({{ $qcFailed }})</span>
            <div class="progress flex-grow-1 me-2" style="height: 20px;">
                <div class="progress-bar bg-danger" role="progressbar"
                    style="width: {{ $qcFailedPercent }}%;"></div>
            </div>
            <span>{{ round($qcFailedPercent,1) }}%</span>
        </div>
    </div>

    {{-- Table: Production Batches --}}
    <div class="mb-4">
        <h5>Production Batches</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Batch #</th>
                    <th>Order ID</th>
                    <th>Produced Qty</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($batches as $batch)
                <tr>
                    <td>Batch{{ $batch->id }}</td>
                    <td>Order{{ $batch->production_order_id }}</td>
                    <td>{{ $batch->produced_quantity }}</td>
                    <td>{{ ucfirst($batch->status) }}</td>
                    <td>
                        @if($batch->status !== 'completed')
                            <a href="{{ route('production_batches.edit', $batch->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        @else
                            <a href="{{ route('quality_controls.show', $batch->qualityControl->id ?? 0) }}" class="btn btn-sm btn-outline-primary">
                            QC Report
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Table: Production Orders --}}
    <div>
        <h5>Production Orders</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Order Ref</th>
                    <th>Product</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->order_id ? 'Order'.$order->order_id : 'Restock' }}</td>
                    <td>{{ $order->product->name }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
