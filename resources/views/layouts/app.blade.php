<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div class="min-vh-100 bg-light">
        @include('layouts.navigation')

        @if(View::hasSection('header'))
            <header class="bg-white shadow-sm">
                <div class="container py-4">
                    @yield('header')
                </div>
            </header>
        @endif

        <main class="container mt-4">
            @yield('content')
        </main>
    </div>

    <!-- Bottom Navigation Bar -->
    <nav class="navbar fixed-bottom navbar-light bg-light border-top shadow-sm">
        <div class="container d-flex justify-content-around">
            <a class="nav-link" href="{{ route('orders.index') }}">Orders</a>
            <a class="nav-link" href="{{ route('inventory.index') }}">Inventory</a>
            <a class="nav-link" href="{{ route('payments.index') }}">Payments</a>
            <a class="nav-link" href="{{ route('shipments.index') }}">Shipments</a>
            <a class="nav-link" href="{{ route('refunds.index') }}">Refunds</a>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
