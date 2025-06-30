{{-- filepath: resources/views/notifications/index.blade.php --}}
<x-app-layout>
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">Notifications</h2>
        <ul class="list-group">
            @forelse($notifications as $notification)
                <li class="list-group-item">
                    {{ $notification->data['message'] ?? 'Notification' }}
                    <span class="text-muted float-end">{{ $notification->created_at->diffForHumans() }}</span>
                </li>
            @empty
                <li class="list-group-item text-muted">No notifications.</li>
            @endforelse
        </ul>
    </div>
</x-app-layout>