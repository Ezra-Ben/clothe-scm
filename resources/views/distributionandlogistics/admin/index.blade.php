@extends('layouts.app')

@section('title', 'Distribution & Logistics Dashboard')

@section('content')
<h1>Distribution & Logistics</h1>
<div class="container-fluid py-3">
    <div class="row g-4">
        <!-- Inbound Shipments -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Inbound Shipments</h5>
                    <a href="{{ route('inbound.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> New Shipment
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Supplier</th>
                                <th>Carrier</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shipments as $shipment)
                                <tr>
                                    <td>{{ $shipment->id }}</td>
                                    <td>{{ $shipment->supplier->name }}</td>
                                    <td>{{ $shipment->carrier->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('inbound.status.change.form', $shipment) }}">
                                            <span class="badge bg-info">{{ $shipment->status }}</span>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('inbound.show', $shipment) }}" class="btn btn-sm btn-info">
                                                Track,
                                            </a>
                                            <a href="{{ route('inbound.edit',$shipment) }}"  class="btn btn-sm btn-info">
                                                Edit,
                                            </a>
                                            @if($shipment->status !== 'received')
                                                <a href="{{ route('inbound.receive.form', $shipment) }}" class="btn btn-sm btn-warning">
                                                    Receive
                                                </a>
                                            @else
                                                <a href="{{ route('inbound.receipt.view', $shipment->id) }}" class="btn btn-sm btn-outline-secondary">View Receipt</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $shipments->links() }}
                </div>
            </div>
        </div>

        <!-- Deliveries -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Delivery Tracking</h5>
                    <a href="{{ route('distributionandlogistics.deliveries.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle"></i> New Delivery
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Carrier</th>
                                    <th>Tracking #</th>
                                    <th>Service Level</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deliveries as $delivery)
                                <tr>
                                    <td>{{ $delivery->order_id }}</td>
                                    <td>{{ $delivery->carrier->name }}</td>
                                    <td><code>{{ $delivery->tracking_number }}</code></td>
                                    <td><span class="badge bg-info text-dark">{{ Str::title($delivery->service_level) }}</span></td>
                                    <td>
                                        <a href="{{ route('distributionandlogistics.deliveries.status.change.form', $delivery) }}">
                                            <span class="badge bg-{{ $delivery->status_color }}">{{ Str::title($delivery->status) }}</span>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('distributionandlogistics.deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Track,
                                        </a>
                                        <a href="{{ route('distributionandlogistics.deliveries.edit', $delivery) }}" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No deliveries found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $deliveries->links() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Carriers -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Carrier Directory</h5>
                    <a href="{{ route('distributionandlogistics.carriers.admin.create') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Carrier
                    </a>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($carriers as $carrier)
                                <tr>
                                    <td><code>{{ $carrier->code }}</code></td>
                                    <td>{{ $carrier->name }}</td>
                                    <td><span class="badge bg-{{ $carrier->is_active ? 'success' : 'secondary' }}">{{ $carrier->is_active ? 'Active' : 'Inactive' }}</span></td>
                                    <td>
                                        <a href="{{ route('distributionandlogistics.carriers.edit', $carrier) }}" class="btn btn-sm btn-outline-secondary">Edit,</a>
                                        <a href="{{ route('distributionandlogistics.carriers.show', $carrier) }}" class="btn btn-sm btn-outline-secondary">Details,</a>
                                        <form action="{{ route('distributionandlogistics.carriers.destroy', $carrier) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to remove this carrier?')">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $carriers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
