<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function notifyUser($userId, $type, $message, $url = null)
    {
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'message' => $message,
             'meta' => ['link' => $url],
            'is_read' => false,
        ]);
    }
}
