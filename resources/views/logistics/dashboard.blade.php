@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-4">Logistics Dashboard</h3>

    @foreach(auth()->user()->unreadNotifications as $notification)
    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <span>{{ $notification->data['message'] }}</span>
        <a href="{{ $notification->data['url'] }}" class="btn btn-primary btn-sm">View</a>
        <form method="POST" action="{{ route('notifications.markRead', $notification->id) }}" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm ms-2">Mark as Read</button>
        </form>
    </div>
    @endforeach

    <!-- Summary Cards -->
    <div class="row mb-4">
        @foreach([
            ['count' => $readyCount, 'label' => 'Ready to Ship', 'icon' => 'bi-truck', 'color' => 'primary'],
            ['count' => $inTransitCount, 'label' => 'In Transit', 'icon' => 'bi-arrow-right-circle', 'color' => 'warning'],
            ['count' => $deliveredCount, 'label' => 'Delivered', 'icon' => 'bi-check-circle', 'color' => 'success'],
            ['count' => $totalCarriers, 'label' => 'Total Carriers', 'icon' => 'bi-people-fill', 'color' => 'info'],
        ] as $card)
        <div class="col-md-3">
            <div class="card text-center shadow-sm border-{{ $card['color'] }}">
                <div class="card-body">
                    <i class="bi {{ $card['icon'] }} display-4 text-{{ $card['color'] }}"></i>
                    <h4>{{ $card['count'] }}</h4>
                    <p class="mb-0">{{ $card['label'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Quick Access Buttons -->
    <div class="row mb-4">
        @foreach([
            ['label' => 'Manage Outbound', 'route' => 'outbound.index', 'icon' => 'bi-box-arrow-up-right', 'color' => 'primary'],
            ['label' => 'Manage Inbound', 'route' => 'inbound.index', 'icon' => 'bi-box-arrow-in-down', 'color' => 'info'],
            ['label' => 'Manage Carriers', 'route' => 'carriers.index', 'icon' => 'bi-truck-front', 'color' => 'warning'],
            ['label' => 'Manage PODs', 'route' => 'pods.index', 'icon' => 'bi-file-earmark-check', 'color' => 'success'],
        ] as $link)
        <div class="col-md-3">
            <a href="{{ route($link['route']) }}" class="btn btn-{{ $link['color'] }} w-100 shadow-sm mb-2 d-flex flex-column align-items-center justify-content-center py-3">
                <i class="bi {{ $link['icon'] }} display-5 mb-2"></i>
                <span class="fw-semibold">{{ $link['label'] }}</span>
            </a>
        </div>
        @endforeach
    </div>

    <!-- Carrier Batches -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h5>Carrier Batches & Destinations</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Carrier</th>
                        <th>Destination</th>
                        <th>Status</th>
                        <th>Batch Count</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($carrierBatches as $batch)
                    @if($batch->batch_count > 0)
                    <tr>
                        <td>{{ $batch->carrier->user->name ?? 'N/A' }}</td>
                        <td>{{ $batch->destination ? $batch->destination : 'Company' }}</td>
                        <td><span class="badge bg-{{ $batch->status == 'delivered' ? 'success' : 'warning' }}">{{ ucfirst($batch->status) }}</span></td>
                        <td>{{ $batch->batch_count }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Deliveries -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h5>Recent Outbound Deliveries</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Carrier</th>
                        <th>Delivered On</th>
                        <th>Destination</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOutboundShipments as $shipment)
                    <tr>
                        <td>{{ $shipment->order->id }}</td>
                        <td>{{ $shipment->order->customer->user->name }}</td>
                        <td>{{ $shipment->carrier->user->name }}</td>
                        <td>{{ $shipment->actual_delivery_date ? $shipment->actual_delivery_date->format('d M Y') : '-' }}</td>
                        <td>{{ $shipment->order->customer->shipping_address }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No recent outbound deliveries.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header">
            <h5>Recent Inbound Deliveries</h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Procurement Request #</th>
                        <th>Supplier</th>
                        <th>Carrier</th>
                        <th>Delivered On</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentInboundShipments as $shipment)
                    <tr>
                        <td>{{ $shipment->procurementRequest->id ?? '-' }}</td>
                        <td>{{ $shipment->supplier->vendor->name ?? '-' }}</td>
                        <td>{{ $shipment->carrier->user->name ?? '-' }}</td>
                        <td>{{ $shipment->actual_delivery_date ? $shipment->actual_delivery_date->format('d M Y') : '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No recent inbound deliveries.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
