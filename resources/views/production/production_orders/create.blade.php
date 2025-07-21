@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Start New Production Order</h1>

    <form action="{{ route('production_orders.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="product_id" class="form-label">Product</label>
            <select name="product_id" id="product_id" class="form-control" required>
                <option value="">Select Product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Production Order</button>
        <a href="{{ route('production_orders.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
