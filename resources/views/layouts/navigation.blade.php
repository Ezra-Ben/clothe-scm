<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom" style="z-index: 1030;">
    <div class="container">
        <!-- Logo -->
        @auth
            @if(auth()->user()->customer)
                <a class="navbar-brand" href="{{ route('home') }}">
                    <x-application-logo height="30" class="d-inline-block align-text-top" />
                </a>
            @elseif(auth()->user()->can('supplier'))
                <a class="navbar-brand" href="{{ route('supplier.dashboard') }}">
                    <x-application-logo height="30" class="d-inline-block align-text-top" />
                </a>
            @else
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <x-application-logo height="30" class="d-inline-block align-text-top" />
                </a>
            @endif
        @else
            <a class="navbar-brand" href="{{ route('home') }}">
                <x-application-logo height="30" class="d-inline-block align-text-top" />
            </a>
        @endauth

        <!-- Hamburger / Toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <!-- Left Nav Links -->
	    @auth
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    @if(auth()->user()->customer)
                        <a href="{{ route('home') }}"
                           class="nav-link fw-semibold dashboard-link">
                           <i class="bi bi-speedometer2 me-1"></i>{{ __('Dashboard') }}
                        </a>
                    @elseif(auth()->user()->can('supplier'))
                        <a href="{{ route('supplier.dashboard') }}"
                           class="nav-link fw-semibold dashboard-link">
                           <i class="bi bi-speedometer2 me-1"></i>{{ __('Dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}"
                           class="nav-link fw-semibold dashboard-link">
                           <i class="bi bi-speedometer2 me-1"></i>{{ __('Dashboard') }}
                        </a>
                    @endif               
                </li>
            </ul>
	    @endauth

            <!-- Right User Dropdown / Auth Buttons -->
            <ul class="navbar-nav ms-auto">
		@auth
                @if(auth()->user()->customer)
                    <li class="nav-item">
                        <a href="{{ route('orders.index') }}" class="btn btn-primary me-2">
                            My Orders
                        </a>
                    </li>
                @endif

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown" style="z-index: 1050;">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                {{ __('Profile') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                @else
                <li class="nav-item">
                    <a href="{{ url('/login') }}" class="btn btn-outline-primary me-2">
                        Sign In
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('welcome') }}" class="btn btn-primary">
                        Sign Up
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
