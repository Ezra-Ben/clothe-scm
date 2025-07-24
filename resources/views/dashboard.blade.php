@extends('layouts.app')

@section('header')
    <div class="text-center">
        <h2 class="fw-bold text-center text-primary mb-1">System Admin Dashboard</h2>
    </div
@endsection

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

<div class="text-center mb-5 pb-3 border-bottom border-2 border-light-subtle">
  <p class="lead text-secondary">Overview of System Metrics and Analytics</p>
</div>

<div class="container py-4">

    <div class="row d-flex align-items-stretch">
        <div class="col-md-3 d-flex flex-column pe-3">
            @include('admin.partials.kpi_cards')
        </div>

        <div class="col-md-9 d-flex flex-column">
            <div class="flex-fill mb-4">
                @include('admin.partials.sales_chart')
            </div>
            <div class="flex-fill">
                @include('admin.partials.recommend_chart')
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-4">
                @include('admin.partials.production_output')
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-4">
                @include('admin.partials.segment_pie')
            </div>
        </div>
    </div>


    <div class="d-flex justify-content-center my-4 flex-wrap gap-3">
        <a href="{{ route('hr.dashboard') }}" class="btn btn-primary">Human Resource</a>
        <a href="{{ route('admin.select.supplier') }}" class="btn btn-primary">Suppliers</a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Products</a>
        <a href="{{ route('production_orders.index') }}" class="btn btn-primary">Production</a>
        <a href="{{ route('inventory.index') }}" class="btn btn-primary">Inventory</a>
        <a href="{{ route('procurement.requests.index') }}" class="btn btn-primary">Procurement</a>
        <a href="{{ route('logistics.dashboard') }}" class="btn btn-primary">Logistics</a>
    </div>


    <div class="row">
        <div class="col-12">
            @include('admin.partials.activity_feed')
        </div>
    </div>

</div>
@endsection
