@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
@endphp
<div class="py-4">
    <div class="container">
<div class="row">
    {{-- KPI Cards Section (1/4) --}}
    <div class="col-md-3">
        @include('admin.partials.kpi_cards')
    </div>

    {{-- Charts Section (3/4) --}}
    <div class="col-md-9">
        @include('admin.partials.sales_chart')
        @include('admin.partials.recommend_chart')
        @include('admin.partials.segment_pie')
        @include('admin.partials.production_output')
    </div>
</div>

<div class="row">
  <div class="col-12">
    @include('admin.partials.activity_feed')
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
        <a href="{{ route('logistics.dashboard') }}" class="btn btn-primary">Logistics Dashboard</a>
</div>

    </div>
</div>
@endsection
