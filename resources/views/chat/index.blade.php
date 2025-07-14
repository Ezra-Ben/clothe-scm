{{-- resources/views/chat/index.blade.php --}}
@extends('layouts.app')
@section('content')
    <h2>Your Conversations</h2>
    <ul>
        @foreach($conversations as $conversation)
            <li>
                <a href="{{ route('chat.show', $conversation->id) }}">
                    Conversation with 
                    {{ $conversation->userOne->id == auth()->id() ? $conversation->userTwo->name : $conversation->userOne->name }}
                </a>
            </li>
        @endforeach
    </ul>
    <h2>Start a New Chat</h2>
    <ul>
        @foreach($users as $user)
            @if($user->id !== auth()->id())
                <li>
                    {{ $user->name }}
                    <form method="POST" action="{{ route('chat.start') }}" style="display:inline;">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        <button type="submit" class="btn btn-sm btn-primary">Start Chat</button>
                    </form>
                </li>
            @endif
        @endforeach
    </ul>
@endsection