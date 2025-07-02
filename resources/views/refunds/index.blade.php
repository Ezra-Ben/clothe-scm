@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        <i class="bi bi-arrow-repeat"></i> {{ __('Refunds') }}
    </h2>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="py-4">
    <div class="container">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('refunds.create') }}" class="btn btn-success shadow">
                <i class="bi bi-plus-circle"></i> Create Refund
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
                                <th>Payment</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($refunds as $refund)
                            <tr>
                                <td>{{ $refund->id }}</td>
                                <td><span class="badge bg-primary">#{{ $refund->order->id ?? '-' }}</span></td>
                                <td><span class="badge bg-info">#{{ $refund->payment->id ?? '-' }}</span></td>
                                <td>{{ $refund->customer->name ?? '-' }}</td>
                                <td><strong>${{ $refund->amount }}</strong></td>
                                <td>
                                    <span class="badge {{ $refund->status === 'Processed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $refund->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('refunds.show', $refund) }}" class="btn btn-sm btn-primary" title="View"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('refunds.edit', $refund) }}" class="btn btn-sm btn-secondary" title="Edit"><i class="bi bi-pencil"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="my-3">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0">No refunds yet.</p>
                                        <a href="{{ route('refunds.create') }}" class="btn btn-outline-primary mt-2">
                                            <i class="bi bi-plus-circle"></i> Create your first refund
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