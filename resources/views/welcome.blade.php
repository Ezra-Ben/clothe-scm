@extends('layouts.app')

@section('content')
    <!-- If logged in, show Dashboard button -->
    @auth
    <div class="bg-success bg-opacity-10 py-2 text-center">
        @can('supplier')
        <a href="{{ route('supplier.dashboard') }}" class="btn btn-success">
            Go to Dashboard
        </a>
	@else
	<a href="{{ route('dashboard') }}" class="btn btn-success">
            Go to Dashboard
        </a>
	@endcan
    </div>
    @endauth

    <!-- Hero Section -->
    <section 
        class="min-vh-100 d-flex align-items-center justify-content-center text-white"
        style="background: url('{{ asset('images/background.jpg') }}') no-repeat center center / cover;">
        <div class="bg-dark bg-opacity-75 p-5 rounded text-center w-100" style="max-width: 600px;">
            <h1 class="display-4 mb-4">{!! nl2br(e("Welcome to \n J-Clothes")) !!}</h1>
            <p class="lead mb-4">
                Discover high-quality textiles and garments crafted for style and durability. Join us today and elevate your fashion business!
            </p>

            @guest
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Register</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">Login</a>
                </div>
            @endguest
        </div>
    </section>

    <!-- Optional Features Section -->
    <section class="py-5 bg-white text-center">
        <div class="container">
            <h2 class="mb-4">Why choose J-Clothes?</h2>
            <p class="mb-5 text-muted">
                Reliable sourcing, premium materials, and a trusted community. Get the textiles and apparel solutions you deserve.
            </p>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Custom Orders</h5>
                            <p class="card-text text-muted">Get textiles tailored to your unique designs and requirements with ease.</p>                                                                          	                </div>
                    </div>
	        </div>
	        <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Quality Fabrics</h5>
                            <p class="card-text text-muted">Access a wide range of premium fabrics, carefully sourced for top-notch quality.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Trusted Partners</h5>
                            <p class="card-text text-muted">Join a network of trusted suppliers and buyers in the textile industry.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
