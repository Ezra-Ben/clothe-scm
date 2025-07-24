@extends('layouts.app')

@section('content')
<div class="d-flex" style="height: 100vh;">
    
    {{-- Sidebar --}}
    <div class="bg-light border-end p-3" style="width: 25%; overflow-y: auto;">
        <h5 class="mb-4">Conversations</h5>

        @forelse($conversations as $conv)
            @php
                $otherUser = $conv->user_one_id === auth()->id() ? $conv->userTwo : $conv->userOne;
            @endphp
            <div class="chat-item py-2 px-3 mb-2 border rounded bg-white"
                 data-id="{{ $conv->id }}"
                 style="cursor: pointer;">
                <div class="fw-bold">{{ $otherUser->name }}</div>
                <small class="text-muted">{{ $conv->updated_at->diffForHumans() }}</small>
            </div>
        @empty
            <p class="text-muted">No conversations yet.</p>
        @endforelse

        <hr>
        <button class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#newConversationModal">
            Start New Conversation
        </button>
    </div>

    {{-- Right pane --}}
    <div class="flex-fill p-3" id="chat-content">
        <div class="text-muted d-flex align-items-center justify-content-center h-100">
            <h4>Select a conversation or start a new one</h4>
        </div>
    </div>
</div>

{{-- Include the modal --}}
@include('chat.partials.modal')
@endsection

@push('scripts')
<script>
    window.CurrentUserId = {{ auth()->id() }};
    window.CurrentUserRole = "{{ auth()->user()->role->name }}";
</script>

<script src="{{ asset('js/chat.js') }}"></script>
@endpush
