@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Contract Details</h2>

    <form method="POST" action="{{ route('manage.supplier.contracts.update', ['id' => $supplier->id, 'contractId' => $contract->id]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Contract Number:</label>
            <input type="text" class="form-control" name="contract_number" value="{{ $contract->contract_number }}" @cannot('manage-suppliers') readonly @endcannot>
        </div>

        <div class="mb-3">
            <label class="form-label">Start Date:</label>
            <input type="date" class="form-control" name="start_date" value="{{ $contract->start_date }}" @cannot('manage-suppliers') readonly @endcannot>
        </div>

        <div class="mb-3">
            <label class="form-label">End Date:</label>
            <input type="date" class="form-control" name="end_date" value="{{ $contract->end_date }}" @cannot('manage-suppliers') readonly @endcannot>
        </div>

        <div class="mb-3">
            <label class="form-label">Status:</label>
            <select class="form-select" name="status" @cannot('manage-suppliers') disabled @endcannot>
                <option value="pending" {{ $contract->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="active" {{ $contract->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="expired" {{ $contract->status == 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Terms:</label>
            <textarea name="terms" class="form-control" rows="4" @cannot('manage-suppliers') readonly @endcannot>{{ $contract->terms }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Payment Terms:</label>
            <input type="text" name="payment_terms" class="form-control" value="{{ $contract->payment_terms }}" @cannot('manage-suppliers') readonly @endcannot>
        </div>

        <div class="mb-3">
            <label class="form-label">Renewal Date:</label>
            <input type="date" name="renewal_date" class="form-control" value="{{ $contract->renewal_date }}" @cannot('manage-suppliers') readonly @endcannot>
        </div>

        <div class="mb-3">
            <label class="form-label">Notes:</label>
            <textarea name="notes" class="form-control" rows="3" @cannot('manage-suppliers') readonly @endcannot>{{ $contract->notes }}</textarea>
        </div>
        
	@can('manage-suppliers')
        <a href="{{ route('manage.supplier.contracts.index', ['id' => $supplier->id]) }}" class="btn btn-primary">
           Back to Contracts
        </a>
        <button type="submit" class="btn btn-success">Update</button>
        @else
        <a href="{{ route('supplier.contracts.index') }}" class="btn btn-primary">
           Back to Contracts
        </a>
	@endcan
    </form>
</div>
@endsection
