<?php

namespace App\Http\Controllers\Chat;

use App\Models\Conversation;
use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    // List all conversations for the authenticated user
    public function index()
    {
     $user = Auth::user();
    $conversations = Conversation::where('user_one_id', $user->id)
        ->orWhere('user_two_id', $user->id)
        ->with(['userOne', 'userTwo'])
        ->get();

    // Filter users based on allowed chat connections
    $users = User::where('id', '!=', $user->id)
        ->whereHas('role', function($q) use ($user) {
            if ($user->role->name === 'customer') {
                $q->whereIn('name', ['carrier', 'inventory_manager']);
            } elseif ($user->role->name === 'inventory_manager') {
                $q->whereIn('name', ['customer', 'supplier']);
            } elseif ($user->role->name === 'carrier') {
                $q->where('name', 'customer');
            } elseif ($user->role->name === 'supplier') {
                $q->where('name', 'inventory_manager');
            }
        })->get();

    return view('chat.index', compact('conversations', 'users'));
    }

    // Show messages for a conversation
    public function show($id)
    {
        $conversation = Conversation::with('messages.sender')->findOrFail($id);

        // Authorization: Only participants can view
        $user = Auth::user();
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }
        if (request()->ajax()) {
        return response()->json([
            'messages' => $conversation->messages->map(function($msg) {
                return [
                    'message' => $msg->message,
                    'sender' => $msg->sender->name,
                    'created_at' => $msg->created_at->toDateTimeString(),
                ];
            })
        ]);
    }

        return view('chat.show', compact('conversation'));
    }

    // Send a message
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $conversation = Conversation::findOrFail($id);
        $user = Auth::user();

        // Authorization: Only participants can send
        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        event(new MessageSent($message));
        $receiverId = ($conversation->user_one_id == $user->id) ? $conversation->user_two_id : $conversation->user_one_id;
$receiver = \App\Models\User::find($receiverId);
$receiver->notify(new \App\Notifications\NewChatMessage($message->message, $user));

        return response()->json($message);
    }

    // Start a new conversation (or get existing)
    public function startConversation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $otherUserId = $request->user_id;

        // Check if conversation already exists
        $conversation = Conversation::where(function ($q) use ($user, $otherUserId) {
            $q->where('user_one_id', $user->id)->where('user_two_id', $otherUserId);
        })->orWhere(function ($q) use ($user, $otherUserId) {
            $q->where('user_one_id', $otherUserId)->where('user_two_id', $user->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => $user->id,
                'user_two_id' => $otherUserId,
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
        return response()->json(['conversation_id' => $conversation->id]);
        }
        return redirect()->route('chat.show', $conversation->id);
    }
}