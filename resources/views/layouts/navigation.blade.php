<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <x-application-logo height="30" class="d-inline-block align-text-top" />
        </a>

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
                    <a href="{{ auth()->user()->can('view-readonly') ? route('supplier.dashboard') : route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('supplier.dashboard') ? 'active' : '' }}">
                       {{ __('Dashboard') }}
                    </a>                
                </li>
            </ul>
	    @endauth

            <!-- Right User Dropdown -->
            <ul class="navbar-nav ms-auto">
		@auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
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
			@endauth
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
