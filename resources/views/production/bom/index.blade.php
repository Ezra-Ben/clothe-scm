@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary">Bill of Materials Management</h1>
        <a href="{{ route('boms.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Create New BOM</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Existing BOMs</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>BOM ID</th>
                            <th>Product</th>
                            <th>Raw Materials Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($boms as $bom)
                        <tr>
                            <td><strong>#{{ $bom->id }}</strong></td>
                            <td>{{ $bom->product->name }}</td>
                            <td>{{ $bom->bomItems->count() }}</td>
                            <td>
                                {{-- If show route is useful, otherwise remove --}}
                                {{-- <a href="{{ route('boms.show', $bom->id) }}" class="btn btn-info btn-sm me-1"><i class="fas fa-eye"></i> View</a> --}}
                                <a href="{{ route('boms.edit', $bom->id) }}" class="btn btn-warning btn-sm me-1"><i class="fas fa-edit"></i> Edit</a>
                                <form action="{{ route('boms.destroy', $bom->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this BOM? This action cannot be undone.')"><i class="fas fa-trash-alt"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No Bills of Materials defined yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection