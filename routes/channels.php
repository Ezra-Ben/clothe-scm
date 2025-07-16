<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;

Broadcast::channel('conversation.{id}', function ($user, $id) {
        if (!$user) {
        \Log::error('Broadcast auth: user is null');
        return false;
    }
    $exists = Conversation::where('id', $id)
    
        ->where(function($q) use ($user) {
            $q->where('user_one_id', $user->id)
              ->orWhere('user_two_id', $user->id);
        })->exists();
        if (!$exists) {
        \Log::error("Broadcast auth: conversation $id not found for user {$user->id}");
    }
    return $exists ? ['id' => $user->id, 'name' => $user->name] : false;
});