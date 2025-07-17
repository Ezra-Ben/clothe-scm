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
            ['label' => 'Manage Outbound', 'route' => 'logistics.orders.outbound.index', 'icon' => 'bi-box-arrow-up-right', 'color' => 'primary'],
            ['label' => 'Manage Inbound', 'route' => 'logistics.orders.inbound.index', 'icon' => 'bi-box-arrow-in-down', 'color' => 'info'],
            ['label' => 'Manage Carriers', 'route' => 'logistics.carriers.index', 'icon' => 'bi-truck-front', 'color' => 'warning'],
            ['label' => 'Manage PODs', 'route' => 'logistics.pods.index', 'icon' => 'bi-file-earmark-check', 'color' => 'success'],
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
                    <tr>
                        <td>{{ $batch->carrier->name ?? 'N/A' }}</td>
                        <td>{{ $batch->destination }}</td>
                        <td><span class="badge bg-{{ $batch->status == 'delivered' ? 'success' : 'warning' }}">{{ ucfirst($batch->status) }}</span></td>
                        <td>{{ $batch->batch_count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Deliveries -->
    <div class="card shadow">
        <div class="card-header">
            <h5>Recent Deliveries</h5>
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
                    @forelse($recentShipments as $shipment)
                    <tr>
                        <td>{{ $shipment->order->id }}</td>
                        <td>{{ $shipment->order->customer->name }}</td>
                        <td>{{ $shipment->carrier->name }}</td>
                        <td>{{ $shipment->actual_delivery_date->format('d M Y') }}</td>
                        <td>{{ $shipment->order->shipping_address }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">No recent deliveries.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
