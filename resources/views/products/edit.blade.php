@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-8">
    <h1 class="text-2xl font-bold text-blue-800 mb-4">Edit Product</h1>
    <x-form-success />
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded border border-red-200">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('products.update', $product) }}" method="POST" class="bg-white p-6 rounded shadow border border-blue-100">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Product Name *</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" placeholder="Enter product name (e.g., Blue T-Shirt)" class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror" required>
            <x-form-error field="name" />
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Description</label>
            <textarea name="description" placeholder="Enter product description (optional)" class="w-full border rounded px-3 py-2 @error('description') border-red-500 @enderror" rows="3">{{ old('description', $product->description) }}</textarea>
            <x-form-error field="description" />
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">SKU *</label>
            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="Enter SKU (e.g., TSHIRT-BLUE-001)" class="w-full border rounded px-3 py-2 @error('sku') border-red-500 @enderror" required>
            <x-form-error field="sku" />
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Price *</label>
            <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" placeholder="0.00" class="w-full border rounded px-3 py-2 @error('price') border-red-500 @enderror" required>
            <x-form-error field="price" />
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Stock Quantity *</label>
            <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" placeholder="0" class="w-full border rounded px-3 py-2 @error('stock') border-red-500 @enderror" required>
            <x-form-error field="stock" />
        </div>
        <div class="flex justify-between">
            <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">Back</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection 