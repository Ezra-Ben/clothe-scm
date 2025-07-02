@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Add New Product</h2>
    <form action="{{ route('products.store') }}" method="POST" class="mt-3">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="sku" class="form-label">SKU</label>
            <input type="text" name="sku" id="sku" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Type</label>
            <select name="type" id="type" class="form-select" required>
                <option value="raw">Raw Material</option>
                <option value="finished">Finished Product</option>
            </select>
        </div>
        {{-- Add more fields as needed --}}
        <button type="submit" class="btn btn-success">Add Product</button>
    </form>
</div>
@endsection