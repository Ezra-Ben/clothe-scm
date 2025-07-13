@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Procurement Request</h1>

    <form method="POST" action="{{ route('procurement.requests.update', $procurementRequest->id) }}">
        @csrf
        @method('PUT')

        {{-- Supplier --}}
        <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control" required>
                <option value="">Select Supplier</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}"
                        {{ $procurementRequest->supplier_id == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Raw Material --}}
        <div class="mb-3">
            <label for="raw_material_id" class="form-label">Raw Material</label>
            <select name="raw_material_id" id="raw_material_id" class="form-control" required>
                <option value="">Select Raw Material</option>
                @foreach ($raw_materials as $material)
                    <option value="{{ $material->id }}"
                        {{ $procurementRequest->raw_material_id == $material->id ? 'selected' : '' }}>
                        {{ $material->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Quantity --}}
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" value="{{ $procurementRequest->quantity }}" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('procurement.requests.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
