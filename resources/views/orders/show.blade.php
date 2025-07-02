@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        {{ __('Order Details') }}
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container">
        <div class="card shadow-sm mb-3">
            <div class="card-body text-dark">
                <h5>Order #{{ $order->id }}</h5>
                <p><strong>Customer:</strong> {{ $order->customer->name ?? '-' }}</p>
                <p><strong>Status:</strong> {{ $order->status }}</p>
                <p><strong>Total:</strong> {{ $order->total }}</p>
                <p><strong>Address:</strong> {{ $order->address->address_line1 ?? '-' }}</p>
            </div>
        </div>
        <div class="card shadow-sm mb-3">
            <div class="card-body text-dark">
                <h6>Order Items</h6>
                <ul>
                    @foreach($order->orderItems as $item)
                        <li>{{ $item->product->name ?? '-' }} (x{{ $item->quantity }}) - {{ $item->price }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="card shadow-sm mb-3">
            <div class="card-body text-dark">
                <h6>Payments</h6>
                <ul>
                    @foreach($order->payments as $payment)
                        <li>{{ $payment->amount }} - {{ $payment->status }} ({{ $payment->method }})</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="card shadow-sm">
            <div class="card-body text-dark">
                <h6>Shipment</h6>
                @if($order->shipment)
                    <p>Status: {{ $order->shipment->status }}</p>
                    <p>Tracking #: {{ $order->shipment->tracking_number }}</p>
                @else
                    <p>No shipment info available.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 