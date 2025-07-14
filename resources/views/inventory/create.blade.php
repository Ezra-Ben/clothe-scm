@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Create New Inventory</h1>
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
            Back to Inventory
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('inventory.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="product_id" class="form-label">Product *</label>
                    <select name="product_id" id="product_id" class="form-control" required>
                        <option value="">Select a Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} - {{ $product->sku }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="quantity_on_hand" class="form-label">Quantity on Hand *</label>
                    <input type="number" name="quantity_on_hand" id="quantity_on_hand" 
                           class="form-control" value="{{ old('quantity_on_hand') }}" 
                           min="0" step="1" required>
                    <div class="form-text">Initial stock quantity available</div>
                </div>

                <div class="mb-3">
                    <label for="quantity_reserved" class="form-label">Quantity Reserved</label>
                    <input type="number" name="quantity_reserved" id="quantity_reserved" 
                           class="form-control" value="{{ old('quantity_reserved', 0) }}" 
                           min="0" step="1">
                    <div class="form-text">Quantity reserved for orders (default: 0)</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Inventory</button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
