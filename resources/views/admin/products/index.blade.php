@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        Manage Products
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container">
  
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Discount %</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>UGX {{ number_format($product->price, 0) }}</td>
                    <td>UGX {{ $product->discount_percent ?? 0 }}%</td>
                    <td>
                        @if ($product->image)
                            <img src="{{ asset('storage/products/' . $product->image) }}" alt="" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;" class="img-thumbnail">
                        @endif
                    </td>
                    <td class="w-auto" style="width: 80px;">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Create button below table --}}
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-3">Add Product</a>
    </div>
</div>
@endsection
