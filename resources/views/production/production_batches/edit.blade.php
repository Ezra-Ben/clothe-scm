@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Production Batch #{{ $batch->id }} (Order #{{ $batch->production_order_id }})</h3>

    <form action="{{ route('production_batches.update', $batch->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="produced_quantity" class="form-label">Produced Quantity</label>
            <input type="number" name="produced_quantity" class="form-control" value="{{ $batch->produced_quantity }}" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="pending" {{ $batch->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ $batch->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ $batch->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ $batch->status == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        {{--
        <button type="button" class="btn btn-outline-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#editScheduleModal"
                data-schedule-id="{{ $schedule->id }}"
                data-description="{{ $schedule->description }}"
                data-start-date="{{ $schedule->start_date->format('Y-m-d') }}"
                data-end-date="{{ $schedule->end_date->format('Y-m-d') }}"
                data-status="{{ $schedule->status }}"
                data-batches="{{ $schedule->batches->pluck('id')->implode(',') }}">
                Edit
        </button>--}}

        <button class="btn btn-primary">Update Batch</button>
        <a href="{{ route('production_orders.show', $batch->production_order_id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
