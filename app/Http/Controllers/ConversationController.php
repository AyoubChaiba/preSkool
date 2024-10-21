<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{

    public function index()
    {
        $conversations = Conversation::where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->with(['sender', 'receiver', 'messages' => function ($query) {
                $query->where('is_read', false);
            }])
            ->get();

        foreach ($conversations as $conversation) {
            $conversation->unread_count = $conversation->messages->count();
        }

        return view('pages.conversations.index', compact('conversations'));
    }

    public function markAsRead($id)
    {
        $conversation = Conversation::findOrFail($id);

        $conversation->messages()->where('receiver_id', auth()->id())->update(['is_read' => true]);

        return response()->json(['message' => 'Messages marked as read.']);
    }

    public function getUsers()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return response()->json($users);
    }

    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $senderId = auth()->id();
        $receiverId = $request->user_id;

        $existingConversation = Conversation::where(function ($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $senderId)
                  ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($senderId, $receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', $senderId);
        })->first();

        if ($existingConversation) {
            return response()->json([
                'message' => 'Conversation already exists!',
                'conversation' => [
                    'id' => $existingConversation->id,
                    'sender_name' => $existingConversation->sender->username,
                    'receiver_name' => $existingConversation->receiver->username,
                ]
            ]);
        }

        $conversation = Conversation::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
        ]);

        return response()->json([
            'message' => 'Conversation created successfully!',
            'conversation' => [
                'id' => $conversation->id,
                'sender_name' => $conversation->sender->username,
                'receiver_name' => $conversation->receiver->username,
            ]
        ]);
    }



}
