@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Procurement Requests</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @can('manage-procurement')
    {{-- Notifications for admin and procurement managers --}}
    @if(auth()->user()->can('manage-procurement') && auth()->user()->unreadNotifications->count() > 0)
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">📢 Recent Notifications</h6>
                <div>
                    <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <form action="{{ route('notifications.markAllRead') }}" method="POST" class="d-inline ms-2">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-secondary">Mark All Read</button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @foreach(auth()->user()->unreadNotifications->take(3) as $notification)
                    <div class="alert alert-info alert-dismissible fade show mb-2" role="alert">
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        <p class="mb-1">{{ $notification->data['message'] }}</p>
                        @if(isset($notification->data['reply_id']))
                            <a href="{{ route('procurement.replies.show', $notification->data['reply_id']) }}" class="btn btn-sm btn-primary" 
                               onclick="markAsRead('{{ $notification->id }}')">
                                Review Reply
                            </a>
                        @elseif(isset($notification->data['request_id']))
                            <a href="{{ route('procurement.requests.show', $notification->data['request_id']) }}" class="btn btn-sm btn-primary"
                               onclick="markAsRead('{{ $notification->id }}')">
                                View Request
                            </a>
                        @endif
                        <form action="{{ route('notifications.markRead', $notification->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-close" aria-label="Close"></button>
                        </form>
                    </div>
                @endforeach
                @if(auth()->user()->unreadNotifications->count() > 3)
                    <p class="text-muted text-center mt-2">
                        <small>And {{ auth()->user()->unreadNotifications->count() - 3 }} more notifications...</small>
                    </p>
                @endif
            </div>
        </div>
    @endif
    @endcan

    @can('manage-procurement')
        <a href="{{ route('procurement.requests.create') }}" class="btn btn-primary mb-3">New Procurement Request</a>
    @endcan

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                @cannot('supplier')
                    <th>Supplier</th>
                @endcannot
                <th>Raw Material</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($requests as $request)
                <tr>
                    <td>{{ $request->id }}</td>
                    @cannot('supplier')
                        <td>{{ $request->supplier->vendor->user->name ?? '-' }}</td>
                    @endcannot
                    <td>{{ $request->rawMaterial->name ?? '-' }}</td>
                    <td>{{ $request->quantity }}</td>
                    <td>{{ ucfirst($request->status) }}</td>
                    <td>{{ $request->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('procurement.requests.show', $request->id) }}" class="btn btn-sm btn-info">
                            Open
                        </a>
                        @can('manage-procurement')
                            <a href="{{ route('procurement.requests.edit', $request->id) }}" class="btn btn-sm btn-warning">
                                Edit
                            </a>
                            <form action="{{ route('procurement.requests.destroy', $request->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
