{{-- resources/views/chat/show.blade.php --}}
@extends('layouts.app')
@section('content')
    <h2>Chat</h2>
    @include('components.chat-widget', ['conversationId' => $conversation->id])
@endsection