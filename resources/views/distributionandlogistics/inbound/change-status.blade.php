@extends('layouts.app')

@section('title', 'Change Shipment Status')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Change Inbound Shipment Status</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('inbound.status.update', $shipment->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="status" class="form-label">New Status</label>
                <select name="status" class="form-control" required>
                    @foreach(['processing', 'in_transit', 'arrived', 'received'] as $status)
                        <option value="{{ $status }}" @selected($shipment->status === $status)>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                Update Status
            </button>
        </form>
    </div>
</div>
@endsection
