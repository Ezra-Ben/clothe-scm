@extends('layouts.app')

@section('title', 'Distribution & Logistics Dashboard')

@section('content')
<h3>Carrier Dashboard</h3>
<div class="container-fluid py-3">
    <div class="row g-4">
        <!-- Inbound Shipments -->

        <div class="container mt-4">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Carrier Profile: {{ $carrier->name }}</h5>
        </div>
        <div class="card-body">

            {{-- Contact Information --}}
            <h6 class="mb-2">📞 Contact Information</h6>
            <p><strong>Phone:</strong> {{ $carrier->contact_phone ?? 'N/A' }}</p>
            @if ($carrier->email)
                <p><strong>Email:</strong> {{ $carrier->email }}</p>
            @endif

            <hr>

            {{-- Service Information --}}
            <h6 class="mb-2">🚚 Service Information</h6>
            <p><strong>Service Areas:</strong></p>
            <ul>
                @foreach (is_array($carrier->service_areas) ? $carrier->service_areas : json_decode($carrier->service_areas ?? '[]') as $area)
                    <li>{{ $area }}</li>
                @endforeach
            </ul>

            <p><strong>Supported Service Levels:</strong></p>
            <ul>
                @foreach (is_array($carrier->supported_service_levels) ? $carrier->supported_service_levels : json_decode($carrier->supported_service_levels ?? '[]') as $level)
                    <li>{{ $level }}</li>
                @endforeach
            </ul>

            <hr>

            {{-- Pricing & Limits --}}
            <h6 class="mb-2">💰 Pricing & Limits</h6>
            <p><strong>Base Rate:</strong> ${{ number_format($carrier->base_rate_usd, 2) }}</p>
            <p><strong>Max Weight:</strong> {{ number_format($carrier->max_weight_kg, 2) }} kg</p>

            <hr>

            {{-- Tracking --}}
            <h6 class="mb-2">🔗 Tracking</h6>
            <p><strong>URL Template:</strong>
                @if ($carrier->tracking_url_template)
                    <a href="{{ $carrier->tracking_url_template }}" target="_blank">{{ $carrier->tracking_url_template }}</a>
                @else
                    <span class="text-muted">N/A</span>
                @endif
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Inbound Shipments</h5>
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
                                            <a href="{{ route('inbound.show', $shipment) }}" class="btn btn-sm btn-info">Track</a>
                                            <a href="{{ route('inbound.carrier.edit',$shipment) }}"  class="btn btn-sm btn-info">Edit</a>
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
                                            <i class="bi bi-eye"></i> Track
                                        </a>
                                        <a href="{{ route('distributionandlogistics.deliveries.carrier.edit', $delivery) }}" class="btn btn-sm btn-outline-primary">
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
    </div>
</div>
@endsection
