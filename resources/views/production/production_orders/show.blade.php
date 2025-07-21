@extends('layouts.app')

@section('content')
<h1>Production Order #{{ $productionOrder->id }}</h1>

<p><strong>Product:</strong> {{ $productionOrder->product->name }}</p>
<p><strong>Quantity:</strong> {{ $productionOrder->quantity }}</p>
<p><strong>Status:</strong> {{ ucfirst($productionOrder->status) }}</p>

@if($productionOrder->order_id)
<p><strong>From Customer Order:</strong> {{ $productionOrder->order_id }}</p>
@endif


<div class="card mb-4">
    <div class="card-header">Required Raw Materials</div>
    <div class="card-body p-0">
        <table class="table table-bordered m-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Quantity Needed</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rawMaterials as $material)
                    <tr>
                        <td>{{ $material->id }}</td>
                        <td>{{ $material->name }}</td>
                        <td>{{ $material->sku }}</td>
                        <td>{{ $material->required_quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@if($productionOrder->status !== 'completed')
<form action="{{ route('production_orders.complete', $productionOrder->id) }}" method="POST">
    @csrf
    <button class="btn btn-success">Mark as Completed</button>
</form>
@endif

@if(!$productionOrder->productionBatch)
    <a href="{{ route('production_batches.create', ['production_order_id' => $productionOrder->id]) }}" class="btn btn-primary mb-3">
        Create Production Batch
    </a>
@else
    <p><strong>Batch Status:</strong> {{ ucfirst($productionOrder->productionBatch->status) }}</p>
    <a href="{{ route('production_batches.edit', $productionOrder->productionBatch->id) }}" class="btn btn-warning mb-3 me-2">
        Edit Batch
    </a>
@endif
<a href="{{ route('production_orders.index') }}" class="btn btn-secondary mb-3">Back</a>
@endsection
