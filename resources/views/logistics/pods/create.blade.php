@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Submit Proof of Delivery</h3>
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('pods.store') }}" method="POST">
        @csrf

        <input type="hidden" name="shipment_id" value="{{ $shipment->id }}">
        <input type="hidden" name="shipment_type" value="{{ $shipmentType }}">

        <div class="mb-3">
            <label class="form-label">Shipment #{{ $shipment->id }} ({{ ucfirst($shipment->status) }})</label>
        </div>

        <div class="mb-3">
            <label for="delivered_by" class="form-label">Delivered By</label>
            <input type="text" name="delivered_by" id="delivered_by" class="form-control" required value="{{ old('delivered_by') }}">
        </div>

        <div class="mb-3">
            <label for="received_by" class="form-label">Received By</label>
            <input type="text" name="received_by" id="received_by" class="form-control" required value="{{ old('received_by') }}">
        </div>

        <div class="mb-3">
            <label for="recipient_name" class="form-label">Recipient Name</label>
            <input type="text" name="recipient_name" id="recipient_name" class="form-control" required value="{{ old('recipient_name') }}">
        </div>

        <div class="mb-3">
            <label for="delivery_notes" class="form-label">Delivery Notes</label>
            <textarea name="delivery_notes" id="delivery_notes" rows="3" class="form-control" placeholder="Enter delivery notes here" required>{{ old('delivery_notes') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="condition" class="form-label">Condition of Shipment</label>
            <textarea name="condition" id="condition" rows="3" class="form-control" placeholder="Describe the condition" required>{{ old('condition') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="discrepancies" class="form-label">Discrepancies (if any)</label>
            <textarea name="discrepancies" id="discrepancies" rows="2" class="form-control" placeholder="Note any discrepancies">{{ old('discrepancies') }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="confirm_delivery" name="confirm_delivery" {{ old('confirm_delivery') ? 'checked' : '' }}>
            <label class="form-check-label" for="confirm_delivery">Confirm Delivery</label>
        </div>

        <div class="form-group">
            <label for="rating">Rate the Carrier (1 - 10)</label>
            <input type="number" name="rating" class="form-control" min="1" max="10" required value="{{ old('rating') }}">
        </div>

        <button type="submit" class="btn btn-primary">Submit PoD</button>
    </form>
</div>
@endsection
