@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-primary">BOM Items for <strong>{{ $bom->product->name }}</strong> (Version {{ $bom->version }})</h1>
        <a href="{{ route('boms.items.create', $bom->id) }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Add Item
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">Raw Materials</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Raw Material</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bom->items as $item)
                            <tr>
                                <td>#{{ $item->id }}</td>
                                <td>{{ $item->rawMaterial->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->unit_of_measure }}</td>
                                <td class="text-end">
                                    <a href="{{ route('boms.items.edit', [$bom->id, $item->id]) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('boms.items.destroy', [$bom->id, $item->id]) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this item?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No BOM items defined.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
