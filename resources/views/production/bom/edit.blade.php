@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-primary">Edit Bill of Materials for <strong>{{ $bom->product->name }}</strong></h1>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit BOM</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('boms.update', $bom->id) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="product_id" class="form-label">Product</label>
                    <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                        <option value="">Select a Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id', $bom->product_id) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="version" class="form-label">Version</label>
                    <input type="text" name="version" id="version" class="form-control @error('version') is-invalid @enderror" value="{{ old('version', $bom->version) }}" required>
                    @error('version')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" required>{{ old('description', $bom->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <h4 class="mt-4 text-info">Raw Materials Required</h4>

                <div id="bom-items-container">
                    @if(old('bom_items'))
                        @foreach(old('bom_items') as $index => $oldItem)
                            @include('production.bom.partials.row', [
                                'index' => $index,
                                'bomItem' => (object) $oldItem,
                                'rawMaterials' => $rawMaterials
                            ])
                        @endforeach
                    @else
                        @foreach($bom->items as $index => $bomItem)
                            @include('production.bom.partials.row', [
                                'index' => $index,
                                'bomItem' => $bomItem,
                                'rawMaterials' => $rawMaterials
                            ])
                        @endforeach
                    @endif
                </div>

                <button type="button" class="btn btn-secondary btn-sm mt-3" id="add-bom-item">
                    <i class="fas fa-plus"></i> Add Raw Material
                </button>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <a href="{{ route('boms.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update BOM</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.rawMaterials = @json($rawMaterials);
    window.initialBomCount = {{ count(old('bom_items', $bom->items)) }};
</script>
<script src="{{ asset('js/bom.js') }}"></script>
@endpush
