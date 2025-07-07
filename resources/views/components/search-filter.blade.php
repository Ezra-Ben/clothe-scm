@props(['route', 'searchPlaceholder' => 'Search...', 'filters' => []])

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ $route }}" class="row g-3">
            <!-- Search Input -->
            <div class="col-md-{{ count($filters) > 0 ? '4' : '8' }}">
                <label for="search" class="form-label">Search</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="{{ $searchPlaceholder }}">
                </div>
            </div>

            <!-- Dynamic Filters -->
            @foreach($filters as $filter)
                <div class="col-md-{{ count($filters) > 0 ? '3' : '4' }}">
                    <label for="{{ $filter['name'] }}" class="form-label">{{ $filter['label'] }}</label>
                    <select class="form-select" id="{{ $filter['name'] }}" name="{{ $filter['name'] }}">
                        <option value="">{{ $filter['placeholder'] ?? 'All' }}</option>
                        @foreach($filter['options'] as $value => $label)
                            <option value="{{ $value }}" {{ request($filter['name']) == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endforeach

            <!-- Filter Button -->
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Clear Filters and Results Count -->
        @if(request('search') || collect($filters)->pluck('name')->map(fn($name) => request($name))->filter()->count() > 0)
            <div class="mt-3">
                <a href="{{ $route }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times me-1"></i>Clear Filters
                </a>
                <small class="text-muted ms-2">
                    Showing filtered results
                </small>
            </div>
        @endif
    </div>
</div> 