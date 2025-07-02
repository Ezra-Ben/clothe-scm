@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        <i class="bi bi-pencil"></i> {{ __('Edit Shipment') }}
    </h2>
@endsection

@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="py-4">
    <div class="container">
        <div class="card shadow-sm mx-auto" style="max-width: 500px;">
            <div class="card-body text-dark">
                <form method="POST" action="{{ route('shipments.update', $shipment) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" name="status" id="status" class="form-control" value="{{ old('status', $shipment->status) }}">
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label for="shipped_at" class="form-label">Shipped At</label>
                            <input type="datetime-local" name="shipped_at" id="shipped_at" class="form-control" value="{{ old('shipped_at', $shipment->shipped_at) }}">
                        </div>
                        <div class="col">
                            <label for="delivered_at" class="form-label">Delivered At</label>
                            <input type="datetime-local" name="delivered_at" id="delivered_at" class="form-control" value="{{ old('delivered_at', $shipment->delivered_at) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tracking_number" class="form-label">Tracking Number</label>
                        <input type="text" name="tracking_number" id="tracking_number" class="form-control" value="{{ old('tracking_number', $shipment->tracking_number) }}">
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update Shipment
                        </button>
                        <a href="{{ route('shipments.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 