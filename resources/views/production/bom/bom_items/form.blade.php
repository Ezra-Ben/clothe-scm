@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="text-primary mb-4">
        {{ isset($bomItem) ? 'Edit BOM Item' : 'Add BOM Item' }} for <strong>{{ $bom->product->name }}</strong>
    </h1>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="{{ isset($bomItem)
                ? route('boms.items.update', [$bom->id, $bomItem->id])
                : route('boms.items.store', $bom->id) }}">
                
                @csrf
                @if(isset($bomItem)) @method('PUT') @endif

                <input type="hidden" name="bom_id" value="{{ $bom->id }}">

                <div class="mb-3">
                    <label for="raw_material_id" class="form-label">Raw Material</label>
                    <select name="raw_material_id" class="form-select @error('raw_material_id') is-invalid @enderror">
                        <option value="">Select Material</option>
                        @foreach($rawMaterials as $rm)
                            <option value="{{ $rm->id }}" {{ (old('raw_material_id', $bomItem->raw_material_id ?? '') == $rm->id) ? 'selected' : '' }}>
                                {{ $rm->name }} ({{ $rm->unit }})
                            </option>
                        @endforeach
                    </select>
                    @error('raw_material_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" step="0.01" min="0.01" class="form-control @error('quantity') is-invalid @enderror"
                        value="{{ old('quantity', $bomItem->quantity ?? '') }}">
                    @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Unit of Measure</label>
                    <input type="text" name="unit_of_measure" class="form-control @error('unit_of_measure') is-invalid @enderror"
                        value="{{ old('unit_of_measure', $bomItem->unit_of_measure ?? '') }}">
                    @error('unit_of_measure') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('boms.items.index', $bom->id) }}" class="btn btn-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">{{ isset($bomItem) ? 'Update' : 'Add' }} Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
