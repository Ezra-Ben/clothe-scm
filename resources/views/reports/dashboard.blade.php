@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        {{ __('Analytics Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Orders</h5>
                        <p class="display-6">{{ $orderCount }}</p>
                        <a href="{{ route('reports.orders') }}" class="btn btn-outline-primary btn-sm">View Orders</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Total Sales</h5>
                        <p class="display-6">${{ number_format($totalSales, 2) }}</p>
                        <a href="{{ route('payments.index') }}" class="btn btn-outline-primary btn-sm">View Payments</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Inventory Count</h5>
                        <p class="display-6">{{ $inventoryCount }}</p>
                        <a href="{{ route('reports.inventory') }}" class="btn btn-outline-primary btn-sm">View Inventory</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 