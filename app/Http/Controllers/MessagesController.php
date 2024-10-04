<?php

namespace App\Http\Controllers;

use App\Models\Messages;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function index() {
        $messages = Messages::where('receiver_id', auth()->id())->orWhere('sender_id', auth()->id())->get();
        return view('pages.messages.list', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $senderId = Auth::user()->id;

        $message = Messages::create([
            'sender_id' => $senderId,
            'receiver_id' => $request->receiver_id,
            'content' => $request->content,
        ]);

        Notifications::create([
            'user_id' => $request->receiver_id,
            'message' => 'You have received a new message from ' . auth()->user()->name,
            'type' => 'in-app',
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Message sent and notification created successfully!',
        ]);
    }
}
