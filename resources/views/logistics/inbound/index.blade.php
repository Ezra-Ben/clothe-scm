@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Inbound Shipment Overview</h3>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-secondary shadow text-center">
                <div class="card-body">
                    <i class="bi bi-box-seam display-4 text-secondary"></i>
                    <h4>{{ $shipments->where('status', 'pending')->count() }}</h4>
                    <p class="card-text">Pending Shipments</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-warning shadow text-center">
                <div class="card-body">
                    <i class="bi bi-arrow-left-right display-4 text-warning"></i>
                    <h4>{{ $shipments->where('status', 'in_transit')->count() }}</h4>
                    <p class="card-text">In Transit</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-success shadow text-center">
                <div class="card-body">
                    <i class="bi bi-check2-square display-4 text-success"></i>
                    <h4>{{ $shipments->where('status', 'delivered')->count() }}</h4>
                    <p class="card-text">Delivered</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Inbound Shipments Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">All Inbound Shipments</h5>
            <table class="table table-bordered table-hover mt-3">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>ETA</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shipments as $shipment)
                    <tr>
                        <td>{{ $shipment->id }}</td>
                        <td>{{ $shipment->supplier->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $shipment->status == 'delivered' ? 'success' : 
                                ($shipment->status == 'in_transit' ? 'warning' : 'secondary') 
                            }}">
                                {{ ucfirst(str_replace('_', ' ', $shipment->status)) }}
                            </span>
                        </td>
                        <td>{{ optional($shipment->estimated_delivery_date)->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('logistics.orders.inbound.show', $shipment->id) }}" class="btn btn-outline-info btn-sm">Open</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
