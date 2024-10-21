<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function getMessages(Conversation $conversation)
    {

        $messages = $conversation->messages()->orderBy('created_at', 'asc')->get();

        return view('pages.conversations.messages', compact('messages', 'conversation'));
    }

    public function store(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => $message->message,
            'created_at' => $message->created_at->format('Y-m-d H:i'),
        ]);
    }

}
