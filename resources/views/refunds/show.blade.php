@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        <i class="bi bi-eye"></i> {{ __('Refund Details') }}
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container">
        <div class="card shadow-sm mb-3 mx-auto" style="max-width: 500px;">
            <div class="card-body text-dark">
                <h5 class="mb-3">Refund #{{ $refund->id }}</h5>
                <p><strong>Order:</strong> <span class="badge bg-primary">#{{ $refund->order->id ?? '-' }}</span></p>
                <p><strong>Payment:</strong> <span class="badge bg-info">#{{ $refund->payment->id ?? '-' }}</span></p>
                <p><strong>Customer:</strong> {{ $refund->customer->name ?? '-' }}</p>
                <p><strong>Amount:</strong> <span class="fw-bold">${{ $refund->amount }}</span></p>
                <p><strong>Reason:</strong> {{ $refund->reason }}</p>
                <p><strong>Status:</strong> <span class="badge {{ $refund->status === 'Processed' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $refund->status }}</span></p>
                <p><strong>Processed At:</strong> {{ $refund->processed_at }}</p>
                <a href="{{ route('refunds.index') }}" class="btn btn-secondary mt-3">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 