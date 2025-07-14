@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        {{ __('Inventory') }}
    </h2>
@endsection

@section('content')
<div class="container py-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">Finished Goods Inventory</h5>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Quantity On Hand</th>
                        <th>Quantity Reserved</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventories as $inventory)
                        <tr>
                            <td>{{ $inventory->product->name }}</td>
                            <td>{{ $inventory->product->sku ?? 'N/A' }}</td>
                            <td>{{ $inventory->quantity_on_hand }}</td>
                            <td>{{ $inventory->quantity_reserved }}</td>
                            <td>{{ $inventory->quantity_on_hand - $inventory->quantity_reserved }}</td>
                            <td>
                                <a href="{{ route('products.show', $inventory->product_id) }}" class="btn btn-sm btn-outline-primary">
                                    View Product
                                </a>
                                <a href="{{ route('inventory.edit', $inventory->id) }}" class="btn btn-sm btn-outline-secondary">
                                    Edit
                                </a>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No inventory records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- If you want to link to add stock manually --}}
            <div class="mt-3">
                <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                    Add New Stock
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
