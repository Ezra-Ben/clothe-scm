@extends('layouts.app')

@section('content')
<div class="container">
    <h2>My Cart</h2>

    @php
        $grandTotal = 0;
    @endphp

    @forelse ($cartItems as $item)
        @php
            $subtotal = $item->product->price * $item->quantity;
            $grandTotal += $subtotal;
        @endphp

        <div class="card mb-2 p-3">
            <div class="d-flex justify-content-between align-items-center">
                {{-- Left: product details --}}
                <div>
                    <strong>{{ $item->product->name }}</strong><br>
                    Quantity: {{ $item->quantity }}<br>
                    <span class="fw-bold">
                        Subtotal: UGX {{ number_format($subtotal, 0) }}
                    </span>

                    <div class="mt-2">
                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control d-inline w-auto">
                            <button class="btn btn-sm btn-primary">Update</button>
                        </form>

                        <form action="{{ route('cart.destroy', $item) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </div>
                </div>

                {{-- Right: product image --}}
                <div>
                    <img src="{{ asset('storage/products/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="80" style="object-fit: cover;">
                </div>
            </div>
        </div>
    @empty
        <p>Your cart is empty.</p>
    @endforelse

    {{--  TOTAL + Buttons --}}
    @if ($cartItems->count())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('home') }}" class="btn btn-secondary">
                Continue Shopping
            </a>

            <h5 class="mb-0">
                Total: UGX {{ number_format($grandTotal, 0) }}
            </h5>
        </div>
        
        <a href="{{ route('checkout.create') }}" class="btn btn-success mt-3">
            Checkout
        </a>

    @else
        <a href="{{ route('home') }}" class="btn btn-secondary mt-2">Continue Shopping</a>
    @endif
</div>
@endsection
