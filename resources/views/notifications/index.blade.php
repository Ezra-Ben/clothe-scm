@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Notifications</h2>
        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-outline-success">Mark All as Read</button>
        </form>
    </div>

    @if($notifications->count() > 0)
   @foreach($notifications as $notification)
    <div class="card mb-3 shadow-sm @if(!$notification->is_read) bg-light @endif">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="card-title mb-1 text-capitalize">{{ $notification->type }} Notification</h5>
                    <p class="card-text text-muted">{{ $notification->message }}</p>
                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                <div class="text-end">
                    @if(isset($notification->meta['link']))
                        <a href="{{ $notification->meta['link'] }}" class="btn btn-sm btn-outline-info mb-2">View</a>
                    @endif

                    @if(!$notification->is_read)
                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">Mark as Read</button>
                        </form>
                    @else
                        <span class="badge bg-secondary">Read</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endforeach

        <div class="d-flex justify-content-center">
            {{ $notifications->links() }}
        </div>
    @else
        <div class="alert alert-info">
            You don’t have any notifications yet.
        </div>
    @endif
</div>
@endsection
