@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Carrier Dashboard</h1>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    {{-- Notification Section --}}
    <div class="mb-4">
        <h4>Notifications</h4>
        @if($notifications->isEmpty())
            <p>No new notifications.</p>
        @else
            <button class="btn btn-sm btn-outline-secondary mb-2" onclick="markAllAsRead()">Mark all as Read</button>
            <ul class="list-group">
                @foreach($notifications as $notification)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            {{ $notification->data['message'] ?? 'New update available.' }}
                            <small class="text-muted ms-2">{{ $notification->created_at->diffForHumans() }}</small>
                        </span>                        
                        <button class="btn btn-sm btn-link text-decoration-none" onclick="markAsRead('{{ $notification->id }}', this)">Mark as Read</button>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Shipment Stats --}}
    <div class="row mb-5">
        @php
            $cardStyle = 'padding: 20px; border-radius: 8px; text-align: center; color: white;';
        @endphp

        <div class="col-md-3">
            <div style="{{ $cardStyle }} background-color: #3490dc;">
                <h2>{{ $toDoInbound->count() }}</h2>
                <p>Inbound - To Do</p>
            </div>
        </div>
        <div class="col-md-3">
            <div style="{{ $cardStyle }} background-color: #38c172;">
                <h2>{{ $completedInbound->count() }}</h2>
                <p>Inbound - Completed</p>
            </div>
        </div>
        <div class="col-md-3">
            <div style="{{ $cardStyle }} background-color: #ffed4a; color: #000;">
                <h2>{{ $toDoOutbound->count() }}</h2>
                <p>Outbound - To Do</p>
            </div>
        </div>
        <div class="col-md-3">
            <div style="{{ $cardStyle }} background-color: #6c757d;">
                <h2>{{ $completedOutbound->count() }}</h2>
                <p>Outbound - Completed</p>
            </div>
        </div>
    </div>

    {{-- Inbound Shipments --}}
    <div class="mb-4">
        <h4>Inbound - To Do</h4>
        <ul class="list-group">
            @forelse($toDoInbound as $shipment)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $shipment->tracking_number ?? 'No Tracking' }} — {{ ucfirst($shipment->status) }}
                    <a href="{{ route('inbound.show', $shipment->id) }}" class="btn btn-sm btn-primary">View</a>
                </li>
            @empty
                <li class="list-group-item">No inbound shipments to do.</li>
            @endforelse
        </ul>
    </div>

    <div class="mb-4">
        <h4>Inbound - Completed</h4>
        <ul class="list-group">
            @forelse($completedInbound as $shipment)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $shipment->tracking_number ?? 'No Tracking' }} — {{ ucfirst($shipment->status) }}
                    <a href="{{ route('inbound.show', $shipment->id) }}" class="btn btn-sm btn-secondary">View</a>
                </li>
            @empty
                <li class="list-group-item">No completed inbound shipments.</li>
            @endforelse
        </ul>
    </div>

    {{-- Outbound Shipments --}}
    <div class="mb-4">
        <h4>Outbound - To Do</h4>
        <ul class="list-group">
            @forelse($toDoOutbound as $shipment)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $shipment->tracking_number ?? 'N/A' }} — {{ ucfirst($shipment->status) }}
                    <a href="{{ route('outbound.show', $shipment->id) }}" class="btn btn-sm btn-primary">View</a>
                </li>
            @empty
                <li class="list-group-item">No outbound shipments to do.</li>
            @endforelse
        </ul>
    </div>

    <div class="mb-4">
        <h4>Outbound - Completed</h4>
        <ul class="list-group">
            @forelse($completedOutbound as $shipment)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $shipment->tracking_number ?? 'N/A' }} — {{ ucfirst($shipment->status) }}
                    <a href="{{ route('outbound.show', $shipment->id) }}" class="btn btn-sm btn-secondary">View</a>
                </li>
            @empty
                <li class="list-group-item">No completed outbound shipments.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
