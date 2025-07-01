<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container-fluid max-w-7xl mx-auto px-4">
    <!-- Logo -->
    <a class="navbar-brand" href="{{ route('dashboard') }}" wire:navigate>
     {{-- Logo --}}
      <x-application-logo class="d-inline-block align-text-top" style="height: 36px; width: auto;" />
    </a>

    <!-- Hamburger / Toggler -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Collapsible Menu -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left Nav Links -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a 
            href="{{ route('dashboard') }}" 
            wire:navigate
            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
          >
            {{ __('Dashboard') }}
          </a>
        </li>
      </ul>

      <!-- Right Settings Dropdown -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a 
            class="nav-link dropdown-toggle text-secondary" 
            href="#" 
            id="navbarDropdown" 
            role="button" 
            data-bs-toggle="dropdown" 
            aria-expanded="false"
          >
            {{ auth()->user()->name }}
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <li>
              <a class="dropdown-item" href="{{ route('profile') }}" wire:navigate>
                {{ __('Profile') }}
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <button wire:click="logout" class="dropdown-item text-start">
                {{ __('Log Out') }}
              </button>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
