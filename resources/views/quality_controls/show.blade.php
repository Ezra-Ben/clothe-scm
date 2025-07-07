@extends('layouts.app')

@section('content')
    <h1>Quality Control Details</h1>
    <p><strong>Batch:</strong> {{ $qualityControl->productionBatch->batch_number ?? 'N/A' }}</p>
    <p><strong>Tester:</strong> {{ $qualityControl->tester->name ?? 'N/A' }}</p>
    <p><strong>Defects Found:</strong> {{ $qualityControl->defects_found }}</p>
    <p><strong>Status:</strong> {{ $qualityControl->status }}</p>
    <p><strong>Tested At:</strong> {{ $qualityControl->tested_at }}</p>
    <p><strong>Notes:</strong> {{ $qualityControl->notes }}</p>
    <a href="{{ route('quality-controls.edit', $qualityControl) }}">Edit</a> |
    <a href="{{ route('quality-controls.index') }}">Back to List</a>
@endsection 