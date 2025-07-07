@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-8">
    <h1 class="text-2xl font-bold text-blue-800 mb-4">Production Batch Details</h1>
    <div class="bg-white p-6 rounded shadow border border-blue-100 mb-4">
        <p><span class="font-semibold text-blue-700">Batch Number:</span> {{ $productionBatch->batch_number }}</p>
        <p><span class="font-semibold text-blue-700">Product:</span> {{ $productionBatch->product->name ?? 'N/A' }}</p>
        <p><span class="font-semibold text-blue-700">Quantity:</span> {{ $productionBatch->quantity }}</p>
        <p><span class="font-semibold text-blue-700">Status:</span> {{ $productionBatch->status }}</p>
        <p><span class="font-semibold text-blue-700">Started At:</span> {{ $productionBatch->started_at }}</p>
        <p><span class="font-semibold text-blue-700">Completed At:</span> {{ $productionBatch->completed_at }}</p>
        <p><span class="font-semibold text-blue-700">Notes:</span> {{ $productionBatch->notes }}</p>
    </div>
    <div class="flex justify-between">
        <a href="{{ route('production-batches.edit', $productionBatch) }}" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500">Edit</a>
        <a href="{{ route('production-batches.index') }}" class="text-blue-600 hover:underline">Back to List</a>
    </div>
</div>
@endsection 