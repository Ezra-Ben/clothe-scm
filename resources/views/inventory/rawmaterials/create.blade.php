@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">Add Raw Material</h2>
@endsection

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('raw-materials.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Material Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="mb-3">
                    <label for="sku" class="form-label">SKU</label>
                    <input type="text" class="form-control" id="sku" name="sku" required>
                </div>

                <div class="mb-3">
                    <label for="unit_of_measure" class="form-label">Unit of Measure</label>
                    <input type="text" class="form-control" id="unit_of_measure" name="unit_of_measure" required>
                </div>

                <div class="mb-3">
                    <label for="quantity_on_hand" class="form-label">Quantity On Hand</label>
                    <input type="number" class="form-control" id="quantity_on_hand" name="quantity_on_hand" required>
                </div>

                <div class="mb-3">
                    <label for="reorder_point" class="form-label">Reorder Point</label>
                    <input type="number" class="form-control" id="reorder_point" name="reorder_point" required>
                </div>

                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
