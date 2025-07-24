@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary">Bill of Materials Management</h1>
        <a href="{{ route('boms.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New BOM
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Existing BOMs</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">BOM ID</th>
                            <th scope="col">Product</th>
                            <th scope="col">Version</th>
                            <th scope="col">Raw Material Count</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($boms as $bom)
                        <tr>
                            <td><strong>{{ $bom->id }}</strong></td>
                            <td>{{ $bom->product->name }}</td>
                            <td>{{ $bom->version }}</td>
                            <td>{{ $bom->items->count() }}</td> 
                            <td>
                                <a href="{{ route('boms.items.index', $bom->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-folder-open"></i> Open
                                </a>
                                <a href="{{ route('boms.edit', $bom->id) }}" class="btn btn-sm btn-warning me-2">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No BOMs defined yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
