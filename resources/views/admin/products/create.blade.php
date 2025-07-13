@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        Add Product
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Discount %</label>
                <input type="number" name="discount_percent" class="form-control">
            </div>

            <div class="mb-3">
                <label>Image</label>
                <input type="file" name="image" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Save Product</button>
        </form>
    </div>
</div>
@endsection
