<?php
// app/Models/Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'type', 'message', 'is_read', 'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'is_read' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function notifyUser($userId, $type, $message, $link = null)
{
    return self::create([
        'user_id' => $userId,
        'type' => $type,
        'message' => $message,
        'is_read' => false,
        'meta' => ['link' => $link],
    ]);
}
}
