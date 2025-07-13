@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Orders</h1>
        <a href="{{ route('home') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Products
        </a>
    </div>

    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Order #{{ $order->id }}</h6>
                            <span class="badge bg-{{ 
                                $order->status == 'completed' ? 'success' : 
                                ($order->status == 'processing' ? 'primary' : 
                                ($order->status == 'paid' ? 'info' :
                                ($order->status == 'cancelled' ? 'danger' : 
                                ($order->status == 'pending_payment' ? 'warning' : 'secondary'))))
                            }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <small class="text-muted">Order Date:</small><br>
                                <strong>{{ $order->created_at->format('M d, Y') }}</strong>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted">Items:</small><br>
                                <strong>{{ $order->items_count }} {{ Str::plural('item', $order->items_count) }}</strong>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted">Total Amount:</small><br>
                                <strong class="text-success">UGX {{ number_format($order->total, 2) }}</strong>
                            </div>
                            
                            @if($order->payment_method)
                                <div class="mb-2">
                                    <small class="text-muted">Payment Method:</small><br>
                                    <strong>{{ ucfirst($order->payment_method) }}</strong>
                                </div>
                            @endif
                            
                            @if($order->fulfillment)
                                <div class="mb-2">
                                    <small class="text-muted">Fulfillment Status:</small><br>
                                    <span class="badge bg-info">{{ ucfirst($order->fulfillment->status ?? 'Pending') }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Orders Summary --}}
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Orders Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3">
                                <h4 class="text-primary">{{ $orders->count() }}</h4>
                                <small class="text-muted">Total Orders</small>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-success">UGX {{ number_format($orders->sum('total'), 2) }}</h4>
                                <small class="text-muted">Total Spent</small>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-info">{{ $orders->filter(function($order) { return $order->fulfillment && $order->fulfillment->status == 'production_planned'; })->count() }}</h4>
                                <small class="text-muted">Processing</small>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-warning">{{ $orders->filter(function($order) { return $order->fulfillment && $order->fulfillment->status == 'ready_for_shipping'; })->count() }}</h4>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- No Orders State --}}
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-cart fa-4x text-muted"></i>
            </div>
            <h3 class="text-muted">No Orders Yet</h3>
            <p class="text-muted mb-4">You haven't placed any orders yet. Start shopping to see your orders here!</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i class="fas fa-home me-1"></i>Go to Dashboard
            </a>
        </div>
    @endif
</div>
@endsection
