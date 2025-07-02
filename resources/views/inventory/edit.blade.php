@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        {{ __('Edit Inventory Item') }}
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body text-dark">
                <form method="POST" action="{{ route('inventory.update', $item) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Product</label>
                        <select name="product_id" id="product_id" class="form-control">
                            <!-- Populate with products, select current -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $item->quantity }}">
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" value="{{ $item->location }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Item</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 