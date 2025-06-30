@props([
    'title',
    'description',
])

<div class="text-center w-100 mb-4">
    <h1 class="display-5 fw-bold">{{ $title }}</h1>
    <p class="lead text-muted">{{ $description }}</p>
</div>
