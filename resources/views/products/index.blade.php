@extends('layouts.app')

@section('content')
<div class="container py-4">
    <x-breadcrumb :items="[
        ['label' => 'Products', 'url' => route('products.index')]
    ]" />
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">
                <i class="fas fa-box me-2"></i>Products
            </h1>
            <p class="text-muted mb-0">Manage your product inventory</p>
        </div>
        <div class="d-flex gap-2">
            <x-export-buttons :route="route('products.index')" />
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>Create Product
            </a>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('products.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Products</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by name, SKU, or description...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="stock_filter" class="form-label">Stock Level</label>
                    <select class="form-select" id="stock_filter" name="stock_filter">
                        <option value="">All Stock Levels</option>
                        <option value="in_stock" {{ request('stock_filter') == 'in_stock' ? 'selected' : '' }}>In Stock (>0)</option>
                        <option value="low_stock" {{ request('stock_filter') == 'low_stock' ? 'selected' : '' }}>Low Stock (<10)</option>
                        <option value="out_of_stock" {{ request('stock_filter') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock (=0)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="price_filter" class="form-label">Price Range</label>
                    <select class="form-select" id="price_filter" name="price_filter">
                        <option value="">All Prices</option>
                        <option value="low" {{ request('price_filter') == 'low' ? 'selected' : '' }}>Under $50</option>
                        <option value="medium" {{ request('price_filter') == 'medium' ? 'selected' : '' }}>$50 - $100</option>
                        <option value="high" {{ request('price_filter') == 'high' ? 'selected' : '' }}>Over $100</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
            @if(request('search') || request('stock_filter') || request('price_filter'))
                <div class="mt-3">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i>Clear Filters
                    </a>
                    <small class="text-muted ms-2">
                        Showing {{ $products->count() }} of {{ $products->total() }} products
                    </small>
                </div>
            @endif
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'id', 'order' => request('sort') == 'id' && request('order') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="text-decoration-none text-dark">
                                    ID
                                    @if(request('sort') == 'id')
                                        <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @else
                                        <i class="fas fa-sort ms-1 text-muted"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => request('sort') == 'name' && request('order') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="text-decoration-none text-dark">
                                    Name
                                    @if(request('sort') == 'name')
                                        <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @else
                                        <i class="fas fa-sort ms-1 text-muted"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'sku', 'order' => request('sort') == 'sku' && request('order') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="text-decoration-none text-dark">
                                    SKU
                                    @if(request('sort') == 'sku')
                                        <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @else
                                        <i class="fas fa-sort ms-1 text-muted"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'order' => request('sort') == 'price' && request('order') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="text-decoration-none text-dark">
                                    Price
                                    @if(request('sort') == 'price')
                                        <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @else
                                        <i class="fas fa-sort ms-1 text-muted"></i>
                                    @endif
                                </a>
                            </th>
                            <th>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'order' => request('sort') == 'stock' && request('order') == 'asc' ? 'desc' : 'asc']) }}" 
                                   class="text-decoration-none text-dark">
                                    Stock
                                    @if(request('sort') == 'stock')
                                        <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @else
                                        <i class="fas fa-sort ms-1 text-muted"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->description)
                                        <br><small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                    @endif
                                </td>
                                <td><code>{{ $product->sku }}</code></td>
                                <td><span class="badge bg-success">${{ number_format($product->price, 2) }}</span></td>
                                <td>
                                    <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-box fa-2x mb-2"></i>
                                        <p>No products found.</p>
                                        <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i>Create your first product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif
    
    <!-- Navigation buttons at the bottom -->
    <div class="mt-4 text-center">
        <div class="btn-group" role="group">
            <a href="{{ route('production-batches.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-industry me-1"></i>Production Batches
            </a>
            <a href="{{ route('quality-controls.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-check-circle me-1"></i>Quality Controls
            </a>
        </div>
    </div>
</div>
@endsection 