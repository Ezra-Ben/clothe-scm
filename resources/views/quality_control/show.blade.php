

@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Quality Control Record #{{ $qc->id }}</h3>

    <ul class="list-group">
        <li class="list-group-item"><strong>Batch ID:</strong> {{ $qc->production_batch_id }}</li>
        <li class="list-group-item"><strong>Status:</strong> <span class="badge bg-{{ $qc->status === 'passed' ? 'success' : 'danger' }}">{{ ucfirst($qc->status) }}</span></li>
        <li class="list-group-item"><strong>Inspection Date:</strong> {{ $qc->inspection_date }}</li>
        <li class="list-group-item"><strong>Defect Count:</strong> {{ $qc->defect_count }}</li>
        <li class="list-group-item"><strong>Notes:</strong> {{ $qc->notes ?? 'N/A' }}</li>
        @if ($qc->status === 'failed')
            <li class="list-group-item">
                <strong>Corrective Action:</strong> {{ $qc->corrective_action_taken ?? 'N/A' }}
            </li>
        @endif
    </ul>

    <a href="{{ route('quality_control.index') }}" class="btn btn-secondary mt-3">Back</a>
</div>
@endsection
