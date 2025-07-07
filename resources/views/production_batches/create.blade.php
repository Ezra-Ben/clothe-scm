@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-8">
    <h1 class="text-2xl font-bold text-blue-800 mb-4">Create Production Batch</h1>
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
    <form action="{{ route('production-batches.store') }}" method="POST" class="bg-white p-6 rounded shadow border border-blue-100">
        @csrf
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Batch Number *</label>
            <input type="text" name="batch_number" value="{{ old('batch_number') }}" placeholder="Enter batch number (e.g., BATCH-001)" class="w-full border rounded px-3 py-2 @error('batch_number') border-red-500 @enderror" required>
            <x-form-error field="batch_number" />
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Product *</label>
            <select name="product_id" class="w-full border rounded px-3 py-2 @error('product_id') border-red-500 @enderror" required>
                <option value="">Select a product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @if(old('product_id') == $product->id) selected @endif>{{ $product->name }}</option>
                @endforeach
            </select>
            <x-form-error field="product_id" />
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Quantity *</label>
            <input type="number" name="quantity" value="{{ old('quantity') }}" placeholder="Enter quantity" class="w-full border rounded px-3 py-2 @error('quantity') border-red-500 @enderror" required>
            <x-form-error field="quantity" />
        </div>
        <div class="mb-4">
            <label class="block text-blue-700">Status:</label>
            <select name="status" class="w-full border rounded px-3 py-2 @error('status') border-red-500 @enderror" required>
                <option value="">Select a status</option>
                <option value="pending" @if(old('status') == 'pending') selected @endif>Pending</option>
                <option value="in_progress" @if(old('status') == 'in_progress') selected @endif>In Progress</option>
                <option value="completed" @if(old('status') == 'completed') selected @endif>Completed</option>
                <option value="cancelled" @if(old('status') == 'cancelled') selected @endif>Cancelled</option>
            </select>
            @error('status')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Started At</label>
            <input type="datetime-local" name="started_at" value="{{ old('started_at') }}" class="w-full border rounded px-3 py-2 @error('started_at') border-red-500 @enderror">
            <x-form-error field="started_at" />
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Completed At</label>
            <input type="datetime-local" name="completed_at" value="{{ old('completed_at') }}" class="w-full border rounded px-3 py-2 @error('completed_at') border-red-500 @enderror">
            <x-form-error field="completed_at" />
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Notes</label>
            <textarea name="notes" placeholder="Enter any additional notes (optional)" class="w-full border rounded px-3 py-2 @error('notes') border-red-500 @enderror" rows="3">{{ old('notes') }}</textarea>
            <x-form-error field="notes" />
        </div>
        <div class="flex justify-between">
            <a href="{{ route('production-batches.index') }}" class="text-blue-600 hover:underline">Back</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
        </div>
    </form>
</div>
@endsection 