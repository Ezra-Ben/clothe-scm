@extends('layouts.app')

@section('content')
<div class="container">
    <h1>New Procurement Request</h1>

    <form action="{{ route('procurement.requests.store') }}" method="POST">
        @csrf

        
        <div class="mb-3">
            <label for="raw_material_id" class="form-label">Raw Material</label>
            <select name="raw_material_id" id="raw_material_id" class="form-control" required>
                <option value="">Select Raw Material</option>
                @foreach ($raw_materials as $material)
                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control" required>
                <option value="">Select Supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->vendor->user->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-success">Create Request</button>
    </form>
</div>
@endsection
