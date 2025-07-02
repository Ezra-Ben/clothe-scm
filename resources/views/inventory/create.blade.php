@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        {{ __('Add Inventory Item') }}
    </h2>
@endsection

@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="py-4">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body text-dark">
                <form method="POST" action="{{ route('inventory.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Product</label>
                        <select name="product_id" id="product_id" class="form-control">
                            <!-- @foreach($products as $product) -->
                            <!-- <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option> -->
                            <!-- @endforeach -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}">
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 