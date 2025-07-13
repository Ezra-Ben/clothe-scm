@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Inventory for {{ $inventory->product->name }}</h1>

    <form method="POST" action="{{ route('inventory.update', $inventory->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="quantity_on_hand" class="form-label">Quantity On Hand</label>
            <input type="number" name="quantity_on_hand" class="form-control" min="0" value="{{ $inventory->quantity_on_hand }}" required>
        </div>

        <div class="mb-3">
            <label for="quantity_reserved" class="form-label">Quantity Reserved</label>
            <input type="number" name="quantity_reserved" class="form-control" min="0" value="{{ $inventory->quantity_reserved }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
