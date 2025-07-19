@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Create Production Batch for Order #{{ request('production_order_id') }}</h3>

    <form action="{{ route('production_batches.store') }}" method="POST">
        @csrf

        <input type="hidden" name="production_order_id" value="{{ request('production_order_id') }}">

        <div class="mb-3">
            <label for="produced_quantity" class="form-label">Produced Quantity</label>
            <input type="number" name="produced_quantity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="pending" selected>Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
            </select>
        </div>

        <button class="btn btn-success">Create Batch</button>
        <a href="{{ route('production_orders.show', request('production_order_id')) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
