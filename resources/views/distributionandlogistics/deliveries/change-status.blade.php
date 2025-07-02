@extends('layouts.app')

@section('title', 'Change Delivery Status')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Change Delivery Status</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('distributionandlogistics.deliveries.status.update', $delivery->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="status" class="form-label">New Status</label>
                <select name="status" id="status" class="form-select" required>
                    @foreach(['pending', 'processing', 'dispatched', 'in_transit', 'out_for_delivery', 'delivered', 'failed'] as $status)
                        <option value="{{ $status }}" @selected($delivery->status === $status)>
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Status</button>
        </form>
    </div>
</div>
@endsection
