@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
@endphp
<div class="py-4">
    <div class="container">
        
<div class="card shadow-sm">
    <div class="card-body text-dark">
        @if ($user->role_id)
            <h5 class="mb-1">Welcome back, {{ $user->name }}!</h5>
        @else
            <h5 class="mb-1">{{ $user->name }}, welcome to J-Clothes!</h5>
        @endif
    </div>
</div>

<div class="card shadow-sm">
     <div class="card-body text-dark">
        @if ($user->role_id)
	    
            @if ($user->role->name === 'admin')
                <p class="mb-0">Manage suppliers, review vendors, and oversee operations.</p>
            @elseif ($user->role->name === 'vendor')
                <p class="mb-0">Check your product listings and manage your supply chain.</p>
            @elseif ($user->role->name === 'carrier')
                <p class="mb-0">Track your deliveries and keep operations moving smoothly.</p>
            @elseif($user->role->name === 'customer')
                <p class="mb-0">View and order products for your business easily.</p>
            @else
                <p class="mb-0">Explore your dashboard and manage your account.</p>
            @endif
	@else
	    <p class="mb-0">
                Start browsing through our textile products and shop with us or apply for a role to join the J-Clothes community.
            </p>
        @endif
    </div>
</div>

<div class="d-flex justify-content-center my-3 gap-3">
    @can('manage-suppliers')
        <a href="{{ route('admin.select.supplier') }}" class="btn btn-primary">Manage Suppliers</a>
    @endcan

    @can('manage-products')
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Manage Products</a>
    @endcan

    @can('manage-production')
        <a href="{{ route('production_orders.index') }}" class="btn btn-primary">Manage Production</a>
    @endcan

    @can('manage-inventory')
        <a href="{{ route('inventory.index') }}" class="btn btn-primary">Manage Inventory</a>
    @endcan

    @can('manage-procurement')
        <a href="{{ route('procurement.requests.index') }}" class="btn btn-primary">Manage Procurement</a>
    @endcan
    
    @if (!auth()->user()->role)
        <a href="{{ route('vendor.register') }}" class="btn btn-primary">Apply as Vendor</a>
        <a href="{{ route('orders.index') }}" class="btn btn-primary">My Orders</a>
    @endif
</div>

    </div>
</div>
@endsection
