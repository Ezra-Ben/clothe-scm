@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2>Select a Supplier to Manage </h2>
    @if ($suppliers->isEmpty())
    <div class="alert alert-warning">No Suppliers to Manage</div>
    @else
    <form action="{{ route('manage.supplier.contracts.index') }}" method="GET">
        <label for="supplier_id" class="form-label">Select Supplier:</label>
        <select name="id" id="supplier_id" class="form-select mb-3">
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">
                   {{ $supplier->vendor->name ?? 'Unnamed Vendor'}}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Manage Contracts</button>
        <a id="createContractBtn" href="#" class="btn btn-success ms-2 disabled" aria-disabled="true">
            Create Contract
        </a>
	<a id="viewPerformanceBtn" href="#" class="btn btn-info ms-2 disabled" aria-disabled="true">
            View Performance
        </a>
    </form>
    @endif
</div>
@push('scripts')
<script src="{{ asset('js/supplier-select.js') }}"></script>
@endpush
@endsection