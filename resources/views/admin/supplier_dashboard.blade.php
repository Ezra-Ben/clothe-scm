@extends('layouts.app')

@section('content') 
@can('view-admin-supplier-dashboard')

<div class="container mt-4">
    <h2>Admin Supplier Dashboard</h2>
<form method="GET" action="" class="row mb-4">
    <div class="col-md-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or email">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-control">
            <option value="">All Statuses</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
    </div>
</form>

<table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Registration Number</th>
            <th>Address</th>
            <th>Contact</th>
            <th>Status</th>
            <th>Rating</th>
            <th>Joined</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($suppliers as $supplier)
            <tr>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->email }}</td>
                <td>{{ $supplier->registration_number }}</td>
                <td>{{ $supplier->address }}</td>
                <td>{{ $supplier->contact }}</td>
                <td>{{ ucfirst($supplier->status) }}</td>
                <td>{{ $supplier->rating ?? 'N/A' }}</td>
                <td>{{ $supplier->created_at->format('Y-m-d') }}</td>
                <td>
                    <a href="{{ route('admin.suppliers.show', $supplier->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this supplier?')">Delete</button>
                    </form>

                    
     @can('upload-contract')
     <a href="{{ route('admin.contracts.upload', $supplier->id) }}" class="btn btn-secondary btn-sm mt-1">Upload Contract</a>
     @endcan

                    
    @can('view-performance-review')
                        <a href="{{ route('admin.reviews.view', $supplier->id) }}" class="btn btn-dark btn-sm mt-1">View Performance</a>
  @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">No suppliers found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</div>
@else
    <div class="alert alert-danger">You are not authorized to view this page.</div>
@endcan
@endsection
