<?php
// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        
            $notifications = Notification::where('user_id', Auth::id())
    ->latest()
    ->paginate(10); // 🔥 paginate() returns a LengthAwarePaginator

        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

       return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead($id)
    {
        $note = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $note->update(['is_read' => true]);

        return response()->json(['status' => 'marked']);
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'all marked']);
    }

    
}
