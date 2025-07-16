@extends('layouts.app')

@section('content')
{{-- Message Notifications --}}
@if(auth()->user()->unreadNotifications->count())
    <div class="position-relative mb-4">
        <button class="btn btn-outline-primary position-relative" type="button" data-bs-toggle="collapse" data-bs-target="#messageNotifications" aria-expanded="false" aria-controls="messageNotifications">
            <i class="bi bi-chat-dots"></i>
            New Messages
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        </button>
        <div class="collapse mt-2" id="messageNotifications">
            <div class="card card-body">
                @foreach(auth()->user()->unreadNotifications as $notification)
                    <div class="alert alert-info alert-dismissible fade show mb-2" role="alert">
                        <strong>
                            <i class="bi bi-person-circle"></i>
                            {{ $notification->data['sender'] }}:
                        </strong>
                        {{ $notification->data['message'] }}
                        <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success ms-2">Mark as read</button>
                        </form>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">Inventory Dashboard</h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary">Add New Product</a>
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Suppliers</h5>
                        <p class="card-text display-6">{{ $supplierCount ?? '--' }}</p>
                        <a href="/suppliers" class="btn btn-primary btn-sm">Manage Suppliers </a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Manage Procurement Requests</h5>
                        <p class="card-text display-6">{{ $pendingProcurements ?? '--' }}</p>
                        <a href="/procurement/requests" class="btn btn-primary btn-sm">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Inventory Items</h5>
                        <p class="card-text display-6">{{ $inventoryCount ?? '--' }}</p>
                        <a href="/inventory" class="btn btn-primary btn-sm">Overview</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Manage Stock</h5>
                        <a href="{{ route('inventory.index') }}" class="btn btn-primary   btn-sm">Manage Stock</a>
                    </div>
                </div>
            </div> 
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Notifications</h5>
                        <p class="card-text display-6">{{ $notificationCount ?? '--' }}</p>
                        <a href="/notifications" class="btn btn-primary btn-sm">Check</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Order Requests</h5>
                        <a href="{{ route('inventory.order.requests') }}" class="btn btn-primary btn-sm">Manage Order Requests</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Manage Suppliers</h5>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-primary btn-sm">Manage Suppliers</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Manage Procurement</h5>
                        <a href="{{ route('procurement.requests.index') }}" class="btn btn-primary btn-sm">Manage Procurement</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button id="open-chat-list"
    type="button"
    data-bs-toggle="modal"
    data-bs-target="#chatUserModal"
    style="position:fixed; inset-block-end:30px; inset-inline-end:30px; z-index:9999; background:#0d6efd; color:white; border:none; border-radius:50%; inline-size:60px; block-size:60px; font-size:2rem; box-shadow:0 2px 8px rgba(0,0,0,0.2);">
    💬
</button>
@if(isset($users))
    @include('components.chat-user-modal', ['users' => $users])
     @include('components.chat-widget', ['conversationId' => null])
@endif
@endsection
