@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h2>Order #SN{{ $order->id }}</h2>

    <p>Status: <strong>{{ ucfirst($order->status) }}</strong></p>
    <p>Payment Method: {{ ucfirst($order->payment_method) }}</p>

    <hr>

    <h5>Items:</h5>
    @foreach ($order->items as $item)
        <div>
            <strong>{{ $item->product->name }}</strong> — 
            Quantity: {{ $item->quantity }} —
            Price: UGX {{ number_format($item->price) }}
        </div>
    @endforeach

    <hr>

    <p>Subtotal: UGX {{ number_format($order->subtotal) }}</p>
    <p>Tax: UGX {{ number_format($order->tax) }}</p>
    <p>Shipping: UGX {{ number_format($order->shipping) }}</p>
    <h5>Total Paid: UGX {{ number_format($order->total) }}</h5>

    <hr>

    <h5>Fulfillment</h5>

    <p>Status: <strong>{{ ucfirst($order->fulfillment->status) }}</strong></p>
    <p>Payment Date: {{ $order->fulfillment->payment_date ?? 'Pending' }}</p>

    @if ($order->fulfillment->status !== 'delivered')
        <p>Estimated Delivery: {{ $order->fulfillment->estimated_delivery_date }}</p>
    @else
        <p>Delivered Date: {{ $order->fulfillment->delivered_date }}</p>
    @endif

    <p>Updated By: {{ $order->fulfillment->updatedBy->name ?? 'System' }} ({{ $order->fulfillment->updated_by_role }})</p>

    <div class="d-flex justify-content-between mt-3">
        <a href="{{ route('home') }}" class="btn btn-primary">Continue Shopping</a>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Exit</a>
    </div>
</div>
@endsection
