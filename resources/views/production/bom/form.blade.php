@extends('layouts.app')

@section('content')
<div class="container py-4">
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
                        <input type="hidden" name="product_id" value="{{ $bom->product_id }}">
                    @endif
                    @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <h4 class="mt-4 text-info">Raw Materials Required</h4>
                <div id="bom-items-container">
                    @if(isset($bom) && $bom->bomItems->count() > 0)
                        @foreach($bom->bomItems as $index => $bomItem)
                            @include('production.bom.partials.row', ['index' => $index, 'bomItem' => $bomItem, 'rawMaterials' => $rawMaterials])
                        @endforeach
                    @else
                        @include('production.bom.partials.row', ['index' => 0, 'rawMaterials' => $rawMaterials])
                    @endif
                </div>

                <button type="button" class="btn btn-secondary btn-sm mt-3" id="add-bom-item">
                    <i class="fas fa-plus"></i> Add Raw Material
                </button>

                <hr class="my-4">
                <div class="d-flex justify-content-end">
                    <a href="{{ route('boms.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">{{ isset($bom) ? 'Update BOM' : 'Create BOM' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.rawMaterials = @json($rawMaterials);
</script>
<script src="{{ asset('js/bom.js') }}"></script>
@endpush

