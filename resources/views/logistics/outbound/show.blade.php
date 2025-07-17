@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Order #{{ $order->id }} Details</h3>

    <div class="mb-3">
        <strong>Customer:</strong> {{ $order->customer->name }}<br>
        <strong>Shipping Address:</strong> {{ $order->shipping_address }}

        @php
          $status = $order->outboundShipment?->status;
        @endphp

        @if ($status === 'in_transit')
            <strong>Estimated Delivery:</strong> {{ $order->outboundShipment?->estimated_delivery_date?->format('Y-m-d') ?? '-' }} <br>
        @elseif ($status === 'delivered')
            <strong>Actual Delivery:</strong> {{ $order->outboundShipment?->actual_delivery_date?->format('Y-m-d H:i') ?? '-' }} <br>
        @endif
    </div>

    <h5>Order Items</h5>
    <ul class="list-group mb-4">
        @foreach($order->items as $item)
            <li class="list-group-item d-flex justify-content-between">
                {{ $item->product->name }} — Quantity: {{ $item->quantity }}
            </li>
        @endforeach
    </ul>

    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#assignCarrierModal">
        Assign Carrier
    </button>

    @include('logistics.partials.modal', [
        'assignCarrierAction' => route('logistics.orders.outbound.show', $order->id),
        'carriers' => $carriers,
        'assignCarrierPostRoute' => fn($carrier) => route('logistics.orders.assign_carrier_post', [$order->id, $carrier->id]),
    ])

    @if(auth()->check() 
        && $order->outboundShipment
        && auth()->user()->id === $order->outboundShipment->carrier?->user_id)
        <a href="{{ route('pods.create', ['shipment' => $order->outboundShipment->id]) }}" 
           class="btn btn-primary mt-3">
            Submit Proof of Delivery
        </a>
    @endif

</div>
@endsection
