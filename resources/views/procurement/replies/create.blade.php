@extends('layouts.app')

@section('content')
<h1>Create Reply for Request #{{ $procurementRequest->id }}</h1>

<form method="POST" action="{{ route('procurement.replies.store', $procurementRequest->id) }}">
    @csrf

    <div class="mb-3">
        <label>Confirmed Quantity</label>
        <input type="number" name="quantity_confirmed" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Expected Delivery Date</label>
        <input type="date" name="expected_delivery_date" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <input type="text" name="status" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Remarks</label>
        <textarea name="remarks" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Submit Reply</button>
</form>
@endsection
