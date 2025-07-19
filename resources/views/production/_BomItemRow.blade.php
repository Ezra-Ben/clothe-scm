<div class="row mb-3 align-items-end bom-item-row border p-2 rounded bg-light">
    <div class="col-md-5">
        <label for="bom_items_{{ $index }}_raw_material_id" class="form-label">Raw Material</label>
        <select name="bom_items[{{ $index }}][raw_material_id]" id="bom_items_{{ $index }}_raw_material_id" class="form-select @error('bom_items.' . $index . '.raw_material_id') is-invalid @enderror">
            <option value="">Select Raw Material</option>
            @foreach($rawMaterials as $rm)
                <option value="{{ $rm->id }}" {{ (isset($bomItem) && $bomItem->raw_material_id == $rm->id) || old('bom_items.' . $index . '.raw_material_id') == $rm->id ? 'selected' : '' }}>
                    {{ $rm->name }} ({{ $rm->unit }})
                </option>
            @endforeach
        </select>
        @error('bom_items.' . $index . '.raw_material_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label for="bom_items_{{ $index }}_quantity" class="form-label">Quantity</label>
        <input type="number" name="bom_items[{{ $index }}][quantity]" id="bom_items_{{ $index }}_quantity" class="form-control @error('bom_items.' . $index . '.quantity') is-invalid @enderror" value="{{ old('bom_items.' . $index . '.quantity', $bomItem->quantity ?? '') }}" step="0.01" min="0.01">
        @error('bom_items.' . $index . '.quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3 text-end">
        <button type="button" class="btn btn-danger remove-bom-item"><i class="fas fa-minus-circle"></i> Remove</button>
    </div>
</div>
