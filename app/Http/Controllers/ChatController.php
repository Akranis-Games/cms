<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $messages = ChatMessage::with('user')->latest()->limit(100)->get()->reverse();
        return view('chat.index', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'channel' => 'nullable|string',
        ]);

        $message = ChatMessage::create([
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'channel' => $validated['channel'] ?? 'general',
        ]);

        broadcast(new \App\Events\ChatMessageSent($message))->toOthers();

        return response()->json($message->load('user'));
    }

    public function getMessages()
    {
        $messages = ChatMessage::with('user')->latest()->limit(100)->get()->reverse();
        return response()->json($messages);
    }
}

