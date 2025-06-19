@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Supplier Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $supplier->vendor->name }} Performance</h2>
        <span class="badge bg-primary">
            Average Rating: {{ $averageRating ?? 'N/A' }}/5
        </span>
    </div>

    <!-- Review Form (Admin Only) -->
    @can('manage_performance')
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('supplier.performance.store', $supplier) }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Rating (1-5)</label>
                    <input type="number" name="rating" min="1" max="5" 
                           class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Evaluation Notes</label>
                    <textarea name="performance_note" class="form-control" 
                              rows="3" maxlength="500" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        </div>
    </div>
    @endcan

    <!-- Performance History -->
    <div class="card">
        <div class="card-header">Performance History</div>
        <div class="card-body">
            @foreach ($performances as $review)
            <div class="border-bottom pb-3 mb-3">
                <div class="d-flex justify-content-between">
                    <strong>Rating: {{ $review->rating }}/5</strong>
                    <small class="text-muted">
                        {{ $review->created_at->format('M d, Y') }} by
                        {{ $review->creator->name }}
                    </small>
                </div>
                <p class="mt-2">{{ $review->performance_note }}</p>
            </div>
            @endforeach

            {{ $performances->links() }}
        </div>
    </div>
</div>
@endsection