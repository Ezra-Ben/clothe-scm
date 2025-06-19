<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body>
    @include('components.layouts.app.sidebar')  <!-- Sidebar stays included -->

    <div class="content">
        @yield('content')  <!-- Inject page content here -->
    </div>

    @fluxScripts
</body>
</html>