@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-8">
    <h1 class="text-2xl font-bold text-blue-800 mb-4">Edit Production Batch</h1>
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('production-batches.update', $productionBatch) }}" method="POST" class="bg-white p-6 rounded shadow border border-blue-100">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-blue-700">Batch Number:</label>
            <input type="text" name="batch_number" value="{{ old('batch_number', $productionBatch->batch_number) }}" class="w-full border rounded px-3 py-2 @error('batch_number') border-red-500 @enderror" required>
            @error('batch_number')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-blue-700">Product:</label>
            <select name="product_id" class="w-full border rounded px-3 py-2 @error('product_id') border-red-500 @enderror" required>
                <option value="">Select a product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @if(old('product_id', $productionBatch->product_id) == $product->id) selected @endif>{{ $product->name }}</option>
                @endforeach
            </select>
            @error('product_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-blue-700">Quantity:</label>
            <input type="number" name="quantity" value="{{ old('quantity', $productionBatch->quantity) }}" class="w-full border rounded px-3 py-2 @error('quantity') border-red-500 @enderror" required>
            @error('quantity')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-blue-700">Status:</label>
            <select name="status" class="w-full border rounded px-3 py-2 @error('status') border-red-500 @enderror" required>
                <option value="">Select a status</option>
                <option value="pending" @if(old('status', $productionBatch->status) == 'pending') selected @endif>Pending</option>
                <option value="in_progress" @if(old('status', $productionBatch->status) == 'in_progress') selected @endif>In Progress</option>
                <option value="completed" @if(old('status', $productionBatch->status) == 'completed') selected @endif>Completed</option>
                <option value="cancelled" @if(old('status', $productionBatch->status) == 'cancelled') selected @endif>Cancelled</option>
            </select>
            @error('status')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-blue-700">Started At:</label>
            <input type="datetime-local" name="started_at" value="{{ old('started_at', $productionBatch->started_at ? $productionBatch->started_at->format('Y-m-d\TH:i') : '') }}" class="w-full border rounded px-3 py-2 @error('started_at') border-red-500 @enderror">
            @error('started_at')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-blue-700">Completed At:</label>
            <input type="datetime-local" name="completed_at" value="{{ old('completed_at', $productionBatch->completed_at ? $productionBatch->completed_at->format('Y-m-d\TH:i') : '') }}" class="w-full border rounded px-3 py-2 @error('completed_at') border-red-500 @enderror">
            @error('completed_at')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-blue-700">Notes:</label>
            <textarea name="notes" class="w-full border rounded px-3 py-2 @error('notes') border-red-500 @enderror">{{ old('notes', $productionBatch->notes) }}</textarea>
            @error('notes')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex justify-between">
            <a href="{{ route('production-batches.index') }}" class="text-blue-600 hover:underline">Back</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection 