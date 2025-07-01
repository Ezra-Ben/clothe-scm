
@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Supplier Contracts</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Contract Number</th>
                <th>Status</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($contracts as $contract)
                <tr>
                    <td>{{ $contract->id }}</td>
                    <td>{{ $contract->contract_number }}</td>
                    <td>
                        <span class="badge 
                            @if($contract->status == 'active') bg-success 
                            @elseif($contract->status == 'expired') bg-danger 
                            @else bg-warning text-dark 
                            @endif">
                            {{ ucfirst($contract->status) }}
                        </span>
                    </td>
                    <td>{{ $contract->start_date }}</td>
                    <td>{{ $contract->end_date }}</td>
                    <td>
                        @can('manage-suppliers')
                            <a href="{{ route('manage.supplier.contracts.show', ['contractId' => $contract->id, 'id' => $supplier->id]) }}" class="btn btn-primary btn-sm">Open</a>
                        @else
                            <a href="{{ route('supplier.contracts.show', $contract->id) }}" class="btn btn-outline-secondary btn-sm">Open</a>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No contracts found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
     @can('manage-suppliers')
        <a href="{{ route('manage.supplier.contracts.create', ['id' => $supplier->id]) }}" class="btn btn-primary btn-sm">Create New Contract</a>
     @endcan
</div>
@endsection
