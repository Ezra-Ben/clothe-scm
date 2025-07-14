<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

Broadcast::channel('conversation.{id}', function ($user, $id) {
    return Conversation::where('id', $id)
        ->where(function($q) use ($user) {
            $q->where('user_one_id', $user->id)
              ->orWhere('user_two_id', $user->id);
        })->exists();
});