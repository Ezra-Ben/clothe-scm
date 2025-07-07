@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-8">
    <h1 class="text-2xl font-bold text-blue-800 mb-4">Product Details</h1>
    <div class="bg-white p-6 rounded shadow border border-blue-100 mb-4">
        <p><span class="font-semibold text-blue-700">Name:</span> {{ $product->name }}</p>
        <p><span class="font-semibold text-blue-700">Description:</span> {{ $product->description }}</p>
        <p><span class="font-semibold text-blue-700">SKU:</span> {{ $product->sku }}</p>
        <p><span class="font-semibold text-blue-700">Price:</span> ${{ number_format($product->price, 2) }}</p>
        <p><span class="font-semibold text-blue-700">Stock:</span> {{ $product->stock }}</p>
    </div>
    <div class="flex justify-between">
        <a href="{{ route('products.edit', $product) }}" class="bg-yellow-400 text-white px-4 py-2 rounded hover:bg-yellow-500">Edit</a>
        <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">Back to List</a>
    </div>
</div>
@endsection 