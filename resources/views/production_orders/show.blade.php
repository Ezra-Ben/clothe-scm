@extends('layouts.app')

@section('content')
<h1>Production Order #{{ $productionOrder->id }}</h1>

<p><strong>Product:</strong> {{ $productionOrder->product->name }}</p>
<p><strong>Quantity:</strong> {{ $productionOrder->quantity }}</p>
<p><strong>Status:</strong> {{ ucfirst($productionOrder->status) }}</p>

@if($productionOrder->order_id)
<p><strong>Customer Order:</strong> {{ $productionOrder->order_id }}</p>
@endif

<h4>Required Raw Materials:</h4>
<ul>
    @foreach($rawMaterials as $rmId => $qty)
        <li>Raw Material ID: {{ $rmId }} → Needed: {{ $qty }}</li>
    @endforeach
</ul>

@if($productionOrder->status !== 'completed')
<form action="{{ route('production_orders.complete', $productionOrder->id) }}" method="POST">
    @csrf
    <button class="btn btn-success">Mark as Completed</button>
</form>
@endif

<a href="{{ route('production_orders.index') }}" class="btn btn-secondary">Back</a>
@endsection
