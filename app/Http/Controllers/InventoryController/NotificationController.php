<?php
namespace App\Http\Controllers\InventoryController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        // Ensure authentication is required for all methods in this controller
        $this->middleware('auth');
    }
    
    public function index()
    {
        // At this point, user should be authenticated due to middleware
        // But let's add a safety check anyway
        $user = auth()->user();
        
        if (!$user) {
            // This shouldn't happen with middleware, but just in case
            abort(401, 'User not authenticated');
        }
        
        // Get all notifications for the authenticated user
        $notifications = $user->notifications()->latest()->get();
        
        return view('InventoryProcurement.Notifications', compact('notifications'));
    }
    
    public function markAsRead(Request $request, $id)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        $notification = $user->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['error' => 'Notification not found'], 404);
    }
    
    public function markAllAsRead()
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
        
        $user->unreadNotifications->markAsRead();
        
        return response()->json(['success' => true]);
    }
}