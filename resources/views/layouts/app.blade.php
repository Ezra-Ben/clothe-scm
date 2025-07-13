<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'J-Clothes') }}</title>
    
    <!-- Favicon - favicon files -->
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Custom Background Styles -->
    <link rel="stylesheet" href="{{ asset('css/background-styles.css') }}">

    @stack('styles')
</head>
<body>
    <div class="min-vh-100">
        @include('layouts.navigation')

        @if(View::hasSection('header'))
            <header class="bg-white shadow-sm">
                <div class="container py-4">
                    @yield('header')
                </div>
            </header>
        @endif

        <main class="container mt-4">
            <div class="content-overlay p-4">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/auto-dismiss.js') }}"></script>
    <script src="{{ asset('js/hide-ship.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    @stack('scripts')
</body>
</html>
