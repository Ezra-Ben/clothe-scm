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
                       <i class="fas fa-tachometer-alt me-1"></i>{{ __('Dashboard') }}
                    </a>                
                </li>
                
                <!-- Supply Chain Management -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="scmDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cogs me-1"></i>Supply Chain
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="scmDropdown">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                                <i class="fas fa-box me-2"></i>Products
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('production-batches.*') ? 'active' : '' }}" href="{{ route('production-batches.index') }}">
                                <i class="fas fa-industry me-2"></i>Production Batches
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('quality-controls.*') ? 'active' : '' }}" href="{{ route('quality-controls.index') }}">
                                <i class="fas fa-check-circle me-2"></i>Quality Control
                            </a>
                        </li>
                    </ul>
                </li>
                
                <!-- Reports & Analytics -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <i class="fas fa-chart-line me-1"></i>Reports
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
