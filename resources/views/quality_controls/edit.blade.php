@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto py-8">
    <h1 class="text-2xl font-bold text-blue-800 mb-4">Edit Quality Control</h1>
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('quality-controls.update', $qualityControl) }}" method="POST" class="bg-white p-6 rounded shadow border border-blue-100">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-blue-700">Batch:</label>
            <select name="production_batch_id" class="w-full border rounded px-3 py-2 @error('production_batch_id') border-red-500 @enderror" required>
                <option value="">Select a batch</option>
                @foreach($productionBatches as $batch)
                    <option value="{{ $batch->id }}" @if(old('production_batch_id', $qualityControl->production_batch_id) == $batch->id) selected @endif>{{ $batch->batch_number }}</option>
                @endforeach
            </select>
            @error('production_batch_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-blue-700">Tester:</label>
            <select name="tester_id" class="w-full border rounded px-3 py-2 @error('tester_id') border-red-500 @enderror" required>
                <option value="">Select a tester</option>
                @foreach($testers as $tester)
                    <option value="{{ $tester->id }}" @if(old('tester_id', $qualityControl->tester_id) == $tester->id) selected @endif>{{ $tester->name }}</option>
                @endforeach
            </select>
            @error('tester_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-blue-700">Defects Found:</label>
            <textarea name="defects_found" class="w-full border rounded px-3 py-2 @error('defects_found') border-red-500 @enderror">{{ old('defects_found', $qualityControl->defects_found) }}</textarea>
            @error('defects_found')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-blue-700">Status:</label>
            <select name="status" class="w-full border rounded px-3 py-2 @error('status') border-red-500 @enderror" required>
                <option value="">Select a status</option>
                <option value="pending" @if(old('status', $qualityControl->status) == 'pending') selected @endif>Pending</option>
                <option value="passed" @if(old('status', $qualityControl->status) == 'passed') selected @endif>Passed</option>
                <option value="failed" @if(old('status', $qualityControl->status) == 'failed') selected @endif>Failed</option>
                <option value="retest" @if(old('status', $qualityControl->status) == 'retest') selected @endif>Retest</option>
            </select>
            @error('status')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-blue-700">Tested At:</label>
            <input type="datetime-local" name="tested_at" value="{{ old('tested_at', $qualityControl->tested_at ? $qualityControl->tested_at->format('Y-m-d\TH:i') : '') }}" class="w-full border rounded px-3 py-2 @error('tested_at') border-red-500 @enderror">
            @error('tested_at')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-blue-700">Notes:</label>
            <textarea name="notes" class="w-full border rounded px-3 py-2 @error('notes') border-red-500 @enderror">{{ old('notes', $qualityControl->notes) }}</textarea>
            @error('notes')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex justify-between">
            <a href="{{ route('quality-controls.index') }}" class="text-blue-600 hover:underline">Back</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection 