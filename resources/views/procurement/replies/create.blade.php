@extends('layouts.app')

@section('content')
<div class="container">
    <h1>CprocurementReate Reply for Request #{{ $procurementRequest->id }}</h1>
    
    <div class="card mbprocurementR3">
        <div class="card-body">
            <h6 class="card-title">Request Details:</h6>
            <p><strong>Raw Material:</strong> {{ $procurementRequest->rawMaterial->name }}</p>
            <p><strong>Requested Quantity:</strong> {{ $procurementRequest->quantity }}</p>
            <p><strong>Status:</strong> <span class="badge bg-{{ $procurementRequest->status === 'accepted' ? 'success' : 'secondary' }}">{{ ucfirst($procurementRequest->status) }}</span></p>
        </div>
    </div>

    <form method="POST" action="{{ route('procurement.replies.store', $procurementRequest->id) }}">
        @csrf

        <div class="mb-3">
            <label for="quantity_confirmed" class="form-label">Confirmed Quantity</label>
            <input type="number" name="quantity_confirmed" id="quantity_confirmed" class="form-control" 
                   max="{{ $procurementRequest->quantity }}" min="0" placeholder="Enter quantity you can supply">
            <div class="form-text">Maximum: {{ $procurementRequest->quantity }} units</div>
        </div>

        <div class="mb-3">
            <label for="expected_delivery_date" class="form-label">Expected Delivery Date</label>
            <input type="date" name="expected_delivery_date" id="expected_delivery_date" class="form-control" 
                   min="{{ date('Y-m-d') }}">
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Reply Status</label>
            <select name="status" id="status" class="form-select">
                <option value="">-- Select Reply Status --</option>
                <option value="quoted">Price Quote</option>
                <option value="confirmed">Confirmed</option>
                <option value="partial">Partial Supply</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea name="remarks" id="remarks" class="form-control" rows="4" 
                      placeholder="Add any additional information, pricing details, terms, etc."></textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Submit Reply</button>
            <a href="{{ route('procurement.requests.show', $procurementRequest->id) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
