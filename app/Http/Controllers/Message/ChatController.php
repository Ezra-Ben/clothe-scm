<?php

namespace App\Http\Controllers\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Events\MessageSent;
use App\Notifications\NewChatMessage;

class ChatController extends Controller
{
    // Show the full chat interface with sidebar and modal
    public function index()
    {
        $user = Auth::user();

        $conversations = Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['userOne', 'userTwo'])
            ->latest('updated_at')
            ->get();

        $users = User::where('id', '!=', $user->id)
            ->whereHas('role', function ($q) use ($user) {
                if ($user->hasRole('customer')) {
                    $q->whereIn('name', ['carrier', 'inventory_manager']);
                } elseif ($user->hasRole('inventory_manager')) {
                    $q->whereIn('name', ['customer', 'supplier']);
                } elseif ($user->hasRole('carrier')) {
                    $q->where('name', 'customer');
                } elseif ($user->hasRole('supplier')) {
                    $q->where('name', 'inventory_manager');
                }
            })
            ->get();

        return view('chat.app', compact('conversations', 'users'));
    }

    // Return just the messages partial for AJAX loading
    public function show($id)
    {
        $conversation = Conversation::with(['messages.sender'])->findOrFail($id);
        $user = Auth::user();

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        return view('chat.partials.messages', [
            'messages' => $conversation->messages,
            'conversation' => $conversation,
        ]);
    }

    // Handle sending a message
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $conversation = Conversation::findOrFail($id);
        $user = Auth::user();

        if ($conversation->user_one_id !== $user->id && $conversation->user_two_id !== $user->id) {
            abort(403);
        }

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        // Send notification
        $receiverId = $conversation->user_one_id == $user->id
            ? $conversation->user_two_id
            : $conversation->user_one_id;

        $receiver = User::find($receiverId);
        $receiver->notify(new NewChatMessage($message->message, $user));

        // Broadcast the message
        event(new MessageSent($message));

        return response()->json([
            'message' => $message->message,
            'sender' => $user->name,
            'created_at' => $message->created_at->format('H:i'),
        ]);
    }

    // Start a new or return existing conversation
    public function startConversation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = Auth::user();
        $otherUserId = $request->user_id;

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

        return response()->json(['conversation_id' => $conversation->id]);
    }
}
