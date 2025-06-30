@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Create New Contract for {{ $supplier->vendor->name ?? 'Supplier' }}</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('manage.supplier.contracts.store', $supplier->id) }}" method="POST" class="needs-validation">
        @csrf

        <div class="mb-3">
            <label class="form-label">Contract Number:</label>
            <input type="text" name="contract_number" class="form-control" required value="{{ old('contract_number') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Start Date:</label>
            <input type="date" name="start_date" class="form-control" required value="{{ old('start_date') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">End Date:</label>
            <input type="date" name="end_date" class="form-control" required value="{{ old('end_date') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Status:</label>
            <select name="status" class="form-select" required>
                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
           </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Terms:</label>
            <textarea name="terms" class="form-control" rows="4" required>{{ old('terms') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Payment Terms:</label>
            <input type="text" name="payment_terms" class="form-control" value="{{ old('payment_terms') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Renewal Date:</label>
            <input type="date" name="renewal_date" class="form-control" value="{{ old('renewal_date') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Additional Notes:</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Save Contract</button>
    </form>
</div>
@endsection
