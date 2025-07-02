@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <h2>Procurement Delivery Confirmation</h2>
        <p>Product: <strong>{{ $request->product->name }}</strong></p>
        <p>Quantity Requested: <strong>{{ $request->quantity }}</strong></p>
        @if($request->status === 'accepted')
            <form method="POST" action="{{ route('supplier.procurement.deliver', $request->id) }}">
                @csrf
                <input type="number" name="delivered_quantity" min="1" max="{{ $request->quantity }}" required>
                <button type="submit" class="btn btn-info">Confirm Delivery</button>
            </form>
        @elseif($request->status === 'delivery_accepted')
            <div class="alert alert-success">Delivery already confirmed. Awaiting admin review.</div>
        @else
            <div class="alert alert-warning">This request cannot be delivered at this stage.</div>
        @endif
    </div>
@endsection