@extends('layouts.app')

@section('content')
<h1>Edit Reply</h1>

<form method="POST" action="{{ route('procurement.replies.update', $reply->id) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Confirmed Quantity</label>
        <input type="number" name="quantity_confirmed" class="form-control" value="{{ $reply->quantity_confirmed }}" required>
    </div>

    <div class="mb-3">
        <label>Expected Delivery Date</label>
        <input type="date" name="expected_delivery_date" class="form-control" value="{{ $reply->expected_delivery_date }}" required>
    </div>

    <div class="mb-3">
        <label>Status</label>
        <input type="text" name="status" class="form-control" value="{{ $reply->status }}" required>
    </div>

    <div class="mb-3">
        <label>Remarks</label>
        <textarea name="remarks" class="form-control">{{ $reply->remarks }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update Reply</button>
</form>
@endsection
