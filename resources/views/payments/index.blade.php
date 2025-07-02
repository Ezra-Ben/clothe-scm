@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        <i class="bi bi-credit-card"></i> {{ __('Payments') }}
    </h2>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="py-4">
    <div class="container">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('payments.create') }}" class="btn btn-success shadow">
                <i class="bi bi-plus-circle"></i> Record Payment
            </a>
        </div>
        <div class="card shadow-sm">
            <div class="card-body text-dark">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
                                <td><span class="badge bg-primary">#{{ $payment->order->id ?? '-' }}</span></td>
                                <td>{{ $payment->customer->name ?? '-' }}</td>
                                <td><strong>${{ $payment->amount }}</strong></td>
                                <td>
                                    <span class="badge {{ $payment->status === 'Paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $payment->status }}
                                    </span>
                                </td>
                                <td>{{ $payment->method }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="my-3">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0">No payments yet.</p>
                                        <a href="{{ route('payments.create') }}" class="btn btn-outline-primary mt-2">
                                            <i class="bi bi-plus-circle"></i> Record your first payment
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 