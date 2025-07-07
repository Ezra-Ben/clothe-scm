@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-8">
    <h1 class="text-2xl font-bold text-blue-800 mb-4">Create Quality Control</h1>
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
    <form action="{{ route('quality-controls.store') }}" method="POST" class="bg-white p-6 rounded shadow border border-blue-100">
        @csrf
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Production Batch *</label>
            @if($productionBatches->isEmpty())
                <div class="text-red-600 mb-2">
                    No production batches available. 
                    <a href="{{ route('production-batches.create') }}" class="underline text-blue-600">Create a batch first.</a>
                </div>
            @endif
            <select name="production_batch_id" class="w-full border rounded px-3 py-2 @error('production_batch_id') border-red-500 @enderror" required @if($productionBatches->isEmpty()) disabled @endif>
                <option value="">Select a production batch</option>
                @foreach($productionBatches as $batch)
                    <option value="{{ $batch->id }}" @if(old('production_batch_id') == $batch->id) selected @endif>{{ $batch->batch_number }}</option>
                @endforeach
            </select>
            <x-form-error field="production_batch_id" />
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Tester *</label>
            <select name="tester_id" class="w-full border rounded px-3 py-2 @error('tester_id') border-red-500 @enderror" required>
                <option value="">Select a tester</option>
                @foreach($testers as $tester)
                    <option value="{{ $tester->id }}" @if(old('tester_id') == $tester->id) selected @endif>{{ $tester->name }}</option>
                @endforeach
            </select>
            <x-form-error field="tester_id" />
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Defects Found</label>
            <textarea name="defects_found" placeholder="Describe any defects found during testing (optional)" class="w-full border rounded px-3 py-2 @error('defects_found') border-red-500 @enderror" rows="3">{{ old('defects_found') }}</textarea>
            <x-form-error field="defects_found" />
        </div>
        <div class="mb-4">
            <label class="block text-blue-700">Status:</label>
            <select name="status" class="w-full border rounded px-3 py-2 @error('status') border-red-500 @enderror" required>
                <option value="">Select a status</option>
                <option value="pending" @if(old('status') == 'pending') selected @endif>Pending</option>
                <option value="passed" @if(old('status') == 'passed') selected @endif>Passed</option>
                <option value="failed" @if(old('status') == 'failed') selected @endif>Failed</option>
                <option value="retest" @if(old('status') == 'retest') selected @endif>Retest</option>
            </select>
            @error('status')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Tested At</label>
            <input type="datetime-local" name="tested_at" value="{{ old('tested_at') }}" class="w-full border rounded px-3 py-2 @error('tested_at') border-red-500 @enderror">
            <x-form-error field="tested_at" />
        </div>
        <div class="mb-4">
            <label class="block text-blue-700 font-medium">Notes</label>
            <textarea name="notes" placeholder="Enter any additional notes (optional)" class="w-full border rounded px-3 py-2 @error('notes') border-red-500 @enderror" rows="3">{{ old('notes') }}</textarea>
            <x-form-error field="notes" />
        </div>
        <div class="flex justify-between">
            <a href="{{ route('quality-controls.index') }}" class="text-blue-600 hover:underline">Back</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save</button>
        </div>
    </form>
</div>
@endsection 