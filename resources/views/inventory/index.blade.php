@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        Inventory Dashboard
    </h2>
@endsection

@section('content')
<div class="container py-4">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    {{-- Summary Cards --}}<div class="row mb-4 g-4">
    @foreach([
        ['color' => 'primary', 'icon' => 'bi-box-fill', 'label' => 'Finished Goods (In Stock)', 'value' => $inventories->sum('quantity_on_hand')],
        ['color' => 'info', 'icon' => 'bi-arrow-down-circle-fill', 'label' => 'Reserved (Finished)', 'value' => $inventories->sum('quantity_reserved')],
        ['color' => 'danger', 'icon' => 'bi-exclamation-circle-fill', 'label' => 'Materials Below Reorder', 'value' => $rawMaterials->filter(fn($m) => $m->quantity_on_hand <= $m->reorder_point)->count()],
        ['color' => 'warning', 'icon' => 'bi-arrow-down', 'label' => 'Products To Restock (<50)', 'value' => $inventories->filter(fn($i) => $i->quantity_on_hand < 50)->count()],
    ] as $card)
        <div class="col-md-6 col-xl-3">
            <div class="card text-center shadow-sm border-{{ $card['color'] }}">
                <div class="card-body">
                    <h6 class="mb-0">{{ $card['label'] }}</h6>
                    <i class="bi {{ $card['icon'] }} display-4 text-{{ $card['color'] }}"></i>
                    <h4 class="fw-bold">{{ $card['value'] }}</h4>
                </div>
            </div>
        </div>
    @endforeach
</div>
</div>

    {{-- Finished Goods Table --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Product Inventory</h5>
                <a href="{{ route('inventory.create') }}" class="btn btn-sm btn-primary">
                    Add New Product Inventory
                </a>
            </div>

            {{-- Search --}}
            <form method="GET" class="mb-3">
                <input type="text" name="product_search" class="form-control" placeholder="Search products...">
            </form>

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Quantity On Hand</th>
                        <th>Reserved</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventories as $inventory)
                        <tr>
                            <td>{{ $inventory->product->name }}</td>
                            <td>{{ $inventory->quantity_on_hand }}</td>
                            <td>{{ $inventory->quantity_reserved }}</td>
                            <td>
                                @if ($inventory->quantity_on_hand < 50)
                                    <span class="badge bg-warning text-dark">Low</span>
                                @else
                                    <span class="badge bg-success">Good</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('products.show', $inventory->product_id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('inventory.edit', $inventory->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No finished goods found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Raw Materials Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Raw Material Inventory</h5>
                <a href="{{ route('raw-materials.create') }}" class="btn btn-sm btn-success">
                    Add Raw Material
                </a>
            </div>

            {{-- Search --}}
            <form method="GET" class="mb-3">
                <input type="text" name="raw_search" class="form-control" placeholder="Search raw materials...">
            </form>

            <table class="table table-bordered table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Unit</th>
                        <th>On Hand</th>
                        <th>Reorder Point</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rawMaterials as $material)
                        <tr>
                            <td>{{ $material->name }}</td>
                            <td>{{ $material->sku }}</td>
                            <td>{{ $material->unit_of_measure }}</td>
                            <td>{{ $material->quantity_on_hand }}</td>
                            <td>{{ $material->reorder_point }}</td>
                            <td>
                                @if ($material->quantity_on_hand <= $material->reorder_point / 2)
                                    <span class="badge bg-danger">Critical</span>
                                @elseif ($material->quantity_on_hand <= $material->reorder_point)
                                    <span class="badge bg-warning text-dark">Low</span>
                                @else
                                    <span class="badge bg-success">Good</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('raw-materials.edit', $material->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('raw-materials.destroy', $material->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Delete this raw material?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No raw materials found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
