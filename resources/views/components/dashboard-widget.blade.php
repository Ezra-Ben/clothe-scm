@props(['title', 'value', 'icon', 'color' => 'primary', 'description' => null])

<div class="card border-0 shadow-sm h-100">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <div class="bg-{{ $color }} bg-opacity-10 rounded-circle p-3">
                    <i class="fas fa-{{ $icon }} text-{{ $color }} fa-lg"></i>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <h6 class="card-title text-muted mb-1">{{ $title }}</h6>
                <h3 class="mb-0 fw-bold">{{ $value }}</h3>
                @if($description)
                    <small class="text-muted">{{ $description }}</small>
                @endif
            </div>
        </div>
    </div>
</div> 