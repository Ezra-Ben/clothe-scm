@extends('layouts.app')

@section('header')
<div class="d-flex justify-content-between align-items-center">
    <h2 class="h4 fw-semibold text-dark mb-0">
       {{ $product->name }}
    </h2>
    <a href="{{ route('cart.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-1">
        <i class="bi bi-cart"></i> Open Cart
    </a>
</div>
@endsection

@section('content')
<div class="py-4">
    <div class="container">

        {{-- Row: Image + Name + Prices --}}
        <div class="row align-items-center mb-3">
            <div class="col-md-6">
                <img src="{{ asset('storage/products/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
            </div>
            <div class="col-md-6">
                <h3>{{ $product->name }}</h3>

                @if($product->discount_percent)
                    <p class="mb-2">
                        <span class="fw-bold d-block">
                            UGX {{ number_format($product->price * (1 - $product->discount_percent / 100), 0) }}
                        </span>
                        <span class="text-muted text-decoration-line-through d-block">
                            UGX {{ number_format($product->price, 0) }}
                        </span>
                    </p>
                @else
                    <p class="fw-bold mb-2">
                        UGX {{ number_format($product->price, 0) }}
                    </p>
                @endif

                @auth
                <form action="{{ route('cart.add', $product) }}" method="POST">
                   @csrf
                   <button type="submit" class="btn btn-primary">Add to Cart</button>
                </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        Add to Cart
                    </a>
                @endauth
            </div>
        </div>

        {{-- Description: full width --}}
        <div class="row mb-5">
            <div class="col-12">
                <h5>Product Details</h5>
                <p>{{ $product->description }}</p>
            </div>
        </div>

        {{-- Similar products --}}
        <h4>Similar Items</h4>
        <div class="row g-4">
            @foreach ($similar as $item)
                <div class="col-6 col-md-3">
                    <a href="{{ route('products.show', $item->id) }}">
                        <img src="{{ asset('storage/products/' . $item->image) }}" class="img-fluid mb-2">
                        <p>{{ $item->name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
