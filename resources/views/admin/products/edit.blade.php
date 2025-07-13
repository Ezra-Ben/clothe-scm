@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        Edit Product
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name', $product->name) }}">
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description" class="form-control">{{old('description', $product->description)}}</textarea>
            </div>

            <div class="mb-3">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="form-control" required value="{{ old('price', $product->price) }}">
            </div>

            <div class="mb-3">
                <label>Discount %</label>
                <input type="number" name="discount_percent" class="form-control" value="{{ old('discount_percent', $product->discount_percent) }}">
            </div>

            <div class="mb-3">
                <label>Image (upload to replace)</label>
                <input type="file" name="image" class="form-control">
                @if($product->image)
                    <small>Current: <img src="{{ asset('storage/products/' . $product->image) }}" alt="" style="height: 100px; width: 120px;"></small>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Save Product</button>
        </form>
    </div>
</div>
@endsection
