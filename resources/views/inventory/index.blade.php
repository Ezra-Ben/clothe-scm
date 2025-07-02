@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        <i class="bi bi-box-seam"></i> {{ __('Inventory') }}
    </h2>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="py-4">
    <div class="container">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('inventory.create') }}" class="btn btn-success shadow">
                <i class="bi bi-plus-circle"></i> Add Item
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body text-dark">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->product->name ?? '-' }}</td>
                                <td><span class="badge bg-info">{{ $item->quantity }}</span></td>
                                <td>{{ $item->location }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="my-3">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0">No inventory items yet.</p>
                                        <a href="{{ route('inventory.create') }}" class="btn btn-outline-primary mt-2">
                                            <i class="bi bi-plus-circle"></i> Add your first item
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 