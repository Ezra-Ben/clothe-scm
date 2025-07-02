@extends('layouts.app')

@section('header')
    <h2 class="h4 fw-semibold text-dark mb-0">
        {{ __('Dashboard') }}
    </h2>
@endsection

@section('content')
<div class="py-4">
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-body text-dark">
                {{ __("You're logged in!") }}
            </div>
        </div>

        <div class="d-flex justify-content-center my-3 gap-3">
        @can('manage-suppliers')
        <a href="{{ route('admin.select.supplier') }}" class="btn btn-primary">
            Manage Suppliers
        </a>
        @endcan
        @can('manage-inventory')
        <a href="{{ route('admin.inventory.dashboard') }}" class="btn btn-primary">
            Manage Inventory
        </a>
        @else
        @if(!auth()->user()->vendor || !auth()->user()->vendor->supplier)
            <a href="{{ route('vendor.register') }}" class="btn btn-primary">
                Apply as Vendor
            </a>
        @endif
        @endcan
    </div>
    </div>
</div>
@endsection
