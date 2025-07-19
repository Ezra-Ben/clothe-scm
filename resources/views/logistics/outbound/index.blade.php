@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Outbound Shipment Overview</h3>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-primary shadow text-center">
                <div class="card-body">
                    <i class="bi bi-truck display-4 text-primary"></i>
                    <h4>{{ $shipments->where('status', 'pending')->count() }}</h4>
                    <p class="card-text">Orders Ready for Shipping</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-warning shadow text-center">
                <div class="card-body">
                    <i class="bi bi-arrow-repeat display-4 text-warning"></i>
                    <h4>{{ $shipments->where('status', 'in_transit')->count() }}</h4>
                    <p class="card-text">Orders In Transit</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-success shadow text-center">
                <div class="card-body">
                    <i class="bi bi-check-circle display-4 text-success"></i>
                    <h4>{{ $shipments->where('status', 'delivered')->count() }}</h4>
                    <p class="card-text">Delivered Orders</p>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mb-3">All Outbound Orders</h5>

    <table class="table table-bordered table-hover table-striped shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Destination</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipments as $shipment)
            <tr>
                <td>{{ $shipment->order->id ?? '-' }}</td>
                <td>{{ $shipment->order->customer->user->name ?? '-' }}</td>
                <td>
                    <span class="badge bg-{{ 
                        $shipment->status === 'pending' ? 'primary' :
                        ($shipment->status === 'in_transit' ? 'warning' : 'success')
                    }}">
                        {{ ucfirst(str_replace('_', ' ', $shipment->status ?? 'unknown')) }}
                    </span>
                </td>
                <td>{{ $shipment->destination ?? '-' }}</td>
                <td>
                    <a href="{{ route('outbound.show', $shipment->id) }}" class="btn btn-sm btn-outline-info">
                        Open
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
