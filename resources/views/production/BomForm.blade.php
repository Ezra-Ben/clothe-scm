@extends('layouts.app')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item"><a href="{{ route('boms.index') }}" class="text-decoration-none text-primary">BOM Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ isset($bom) ? 'Edit' : 'Create' }} BOM</li>
        </ol>
    </nav>

    <h1 class="mb-4 text-primary">{{ isset($bom) ? 'Edit' : 'Create' }} Bill of Materials</h1>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">{{ isset($bom) ? 'Edit BOM for ' . $bom->product->name : 'Create New BOM' }}</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ isset($bom) ? route('boms.update', $bom->id) : route('boms.store') }}">
                @csrf
                @if(isset($bom))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="product_id" class="form-label">Product</label>
                    <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" {{ isset($bom) ? 'disabled' : '' }}>
                        <option value="">Select a Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ (isset($bom) && $bom->product_id == $product->id) || old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    @if(isset($bom))
                        {{-- Hidden input to ensure product_id is sent if disabled --}}
                        <input type="hidden" name="product_id" value="{{ $bom->product_id }}">
                    @endif
                    @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <h4 class="mt-4 text-info">Raw Materials Required</h4>
                <div id="bom-items-container">
                    @if(isset($bom) && $bom->bomItems->count() > 0)
                        @foreach($bom->bomItems as $index => $bomItem)
                            @include('production._BomItemRow', ['index' => $index, 'bomItem' => $bomItem, 'rawMaterials' => $rawMaterials])
                        @endforeach
                    @else
                        @include('production._BomItemRow', ['index' => 0, 'rawMaterials' => $rawMaterials])
                    @endif
                </div>
                <button type="button" class="btn btn-secondary btn-sm mt-3" id="add-bom-item"><i class="fas fa-plus"></i> Add Raw Material</button>

                <hr class="my-4">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('boms.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">{{ isset($bom) ? 'Update BOM' : 'Create BOM' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let bomItemIndex = {{ (isset($bom) && $bom->bomItems->count() > 0) ? $bom->bomItems->count() : 1 }};
        const bomItemsContainer = document.getElementById('bom-items-container');
        const addBomItemButton = document.getElementById('add-bom-item');

        addBomItemButton.addEventListener('click', function () {
            const newRow = document.createElement('div');
            // Using a hidden template element or dynamic string replacement
            const rawMaterialsOptions = `
                @foreach($rawMaterials as $rm)
                    <option value="{{ $rm->id }}">{{ $rm->name }} ({{ $rm->unit }})</option>
                @endforeach
            `;
            let newRowHtml = `
                <div class="row mb-3 align-items-end bom-item-row border p-2 rounded bg-light">
                    <div class="col-md-5">
                        <label for="bom_items_${bomItemIndex}_raw_material_id" class="form-label">Raw Material</label>
                        <select name="bom_items[${bomItemIndex}][raw_material_id]" id="bom_items_${bomItemIndex}_raw_material_id" class="form-select">
                            <option value="">Select Raw Material</option>
                            ${rawMaterialsOptions}
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="bom_items_${bomItemIndex}_quantity" class="form-label">Quantity</label>
                        <input type="number" name="bom_items[${bomItemIndex}][quantity]" id="bom_items_${bomItemIndex}_quantity" class="form-control" value="" step="0.01" min="0.01">
                    </div>
                    <div class="col-md-3 text-end">
                        <button type="button" class="btn btn-danger remove-bom-item"><i class="fas fa-minus-circle"></i> Remove</button>
                    </div>
                </div>
            `;
            newRow.innerHTML = newRowHtml;
            bomItemsContainer.appendChild(newRow);
            bomItemIndex++;
        });

        bomItemsContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-bom-item') || e.target.closest('.remove-bom-item')) {
                e.target.closest('.bom-item-row').remove();
            }
        });
    });
</script>
@endpush
@endsection
