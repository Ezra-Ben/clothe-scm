@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">Inventory Overview</h2>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div>
        @endif

        {{-- Add Product Form (only for admin/inventory manager) --}}
        @can('manage-inventory')
        <div class="mb-4">
            <form action="{{ route('inventory.add') }}" method="POST" class="row g-2 align-items-end">
                @csrf
                <div class="col-auto">
                    <label for="product_id" class="form-label">Product</label>
                    <select name="product_id" id="product_id" class="form-select" required>
                        @foreach(\App\Models\Product::all() as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-success">Add to Stock</button>
                </div>
            </form>
        </div>
        @endcan

        <table class="table table-bordered bg-white">
            <thead class="table-primary">
                <tr>
                    <th>Product</th>
                    <th>Type</th>
                    <th>SKU</th>
                    <th>Quantity</th>
                    @can('manage-inventory')
                        <th>Actions</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
            @foreach($inventories as $inventory)
                <tr>
                    <td>{{ $inventory->product->name }}</td>
                    <td>
                        <span class="badge {{ $inventory->product->type === 'raw' ? 'bg-info' : 'bg-primary' }}">
                            {{ ucfirst($inventory->product->type) }}
                        </span>
                    </td>
                    <td>{{ $inventory->product->sku }}</td>
                    <td>{{ $inventory->quantity }}</td>
                    @can('manage-inventory')
                    <td>
                        <form action="{{ route('inventory.delete', $inventory->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product from inventory?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                    @endcan
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@if(isset($users))
    @include('components.chat-user-modal', ['users' => $users])
@endif
@endsection