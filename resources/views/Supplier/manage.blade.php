@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h2>Select a Supplier to Manage Contracts</h2>

    <form action="{{ route('manage.supplier.contracts.index') }}" method="GET">
        <label for="supplier_id" class="form-label">Select Supplier:</label>
        <select name="id" id="supplier_id" class="form-select mb-3">
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->vendor->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Manage Contracts</button>
    </form>
</div>
@endsection