@extends('layouts.app')

@section('header')
<div class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
    <h2 class="h4 fw-semibold text-dark mb-0">
        {{ __('Our Products') }}
    </h2>

    <form action="{{ route('home') }}" method="GET" class="flex-grow-1 mx-3">
        <div class="input-group">
            <input 
                type="text" 
                name="search"
                class="form-control"
                placeholder="Search products..."
                value="{{ request('search') }}"
            >
            <button class="btn btn-outline-secondary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </form>

    @auth
        <a href="{{ route('cart.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-1">
            <i class="bi bi-cart"></i> Open Cart
        </a>
    @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary d-flex align-items-center gap-1">
            <i class="bi bi-cart"></i> Open Cart
        </a>
    @endauth
</div>
@endsection


@section('content')
@if(!request('search') || trim(request('search')) === '')
{{-- Carousel Section - Only show when not searching --}} 
<div class="bg-light py-4 mb-4">
    <div class="container">
        <div id="productSlideshow" class="carousel slide" data-bs-ride="carousel" style="height: 160px; overflow: hidden;">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('products/t-shirts.webp') }}" class="d-block w-100" style="object-fit: cover; height: 160px;" alt="T-Shirts">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Premium T-Shirts</h5>
                        <p>High-quality cotton t-shirts for everyday comfort</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('products/organic.jpg') }}" class="d-block w-100" style="object-fit: cover; height: 160px;" alt="Organic">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Organic Collection</h5>
                        <p>Eco-friendly fabrics made from organic materials</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('products/fabrics.jpg') }}" class="d-block w-100" style="object-fit: cover; height: 160px;" alt="Fabrics">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Premium Fabrics</h5>
                        <p>Wide selection of quality fabrics for all your needs</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('products/linen.jpg') }}" class="d-block w-100" style="object-fit: cover; height: 160px;" alt="Linen">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Pure Linen</h5>
                        <p>Breathable and comfortable linen for office wear</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#productSlideshow" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </a>
            <a class="carousel-control-next" href="#productSlideshow" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </a>

            {{-- Central overlay button - positioned at bottom of carousel --}}
            <div class="carousel-caption d-flex justify-content-center align-items-center h-100" style="transform: translateY(45%); z-index: 5;">
                @auth
                    @if(auth()->user()->customer)
                        <a href="{{ route('home') }}" class="btn btn-outline-light">
                            Browse Catalog
                        </a>
                    @endif
                @else
                    <a href="{{ route('welcome') }}" class="btn btn-outline-light">
                        Sign In to Shop
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

{{-- Thin separator line - only show when carousel is visible --}}
<hr class="my-0" style="border-top: 2px solid #6c757d;">
@endif

{{-- Public Product Catalog --}}

<div class="py-4">
    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="container">

    {{-- Recommended Products Section --}}
    @if(auth()->check() && count($recommended) > 0)
    <div class="container mb-5">
    <h3 class="mb-4">
        <i class="bi bi-stars text-warning me-1"></i> Recommended for You
    </h3>

    <div class="overflow-auto pb-2">
        <div class="d-flex flex-nowrap gap-4">
            @foreach ($recommended as $product)
                <div class="card shadow-sm position-relative h-100 recommended-card">
                    {{-- Badge --}}
                    <span class="badge bg-primary position-absolute top-0 start-0 m-2">
                        Recommended
                    </span>

                    {{-- Product image --}}
                    <a href="{{ route('products.show', $product->id) }}">
                        <img src="{{ asset('storage/products/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover; object-position: center;">
                    </a>

                    {{-- Card Body --}}
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1">{{ $product->name }}</h6>

                        <div class="mb-auto">
                            @if($product->discount_percent)
                                <p class="card-text mb-1">
                                    <span class="fw-bold d-block">
                                        UGX {{ number_format($product->price * (1 - $product->discount_percent / 100), 0) }}
                                    </span>
                                    <span class="text-muted text-decoration-line-through d-block">
                                        UGX {{ number_format($product->price, 0) }}
                                    </span>
                                </p>
                            @else
                                <p class="card-text mb-1 fw-bold">
                                    UGX {{ number_format($product->price, 0) }}
                                </p>
                            @endif
                        </div>

                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-primary w-100 mt-auto">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


{{-- Thin separator line - only show when carousel is visible --}}
<hr class="my-0" style="border-top: 2px solid #6c757d;">
@endif

        {{-- Main Grid--}}
        <div class="row g-4">
            @forelse ($products as $product)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card shadow-sm position-relative h-100">
                        {{-- Discount corner --}}
                        @if($product->discount_percent)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                -{{ $product->discount_percent }}%
                            </span>
                        @endif

                        {{-- Product image --}}
                        <a href="{{ route('products.show', $product->id) }}">
                            <img src="{{ asset('storage/products/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="width: 100%; height: 300px; object-fit: cover; object-position: center;">
                        </a>

                        {{-- Card Body --}}
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-1">{{ $product->name }}</h5>

                            {{-- Price --}}
                            <div class="mb-auto">
                                @if($product->discount_percent)
                                    <p class="card-text mb-1">
                                       {{-- Discounted price --}}
                                       <span class="fw-bold d-block">
                                          UGX {{ number_format($product->price * (1 - $product->discount_percent / 100), 0) }}
                                       </span>
                                       {{-- Original price below --}}
                                       <span class="text-muted text-decoration-line-through d-block">
                                          UGX {{ number_format($product->price, 0) }}
                                       </span>
                                   </p>
                                @else
                                   <p class="card-text mb-1 fw-bold">
                                        UGX {{ number_format($product->price, 0) }}
                                        {{-- Empty span to maintain consistent height --}}
                                        <span class="d-block" style="height: 1.5rem;">&nbsp;</span>
                                   </p>
                                @endif
                            </div>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-primary w-100 mt-auto">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p>No products found.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
