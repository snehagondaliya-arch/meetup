<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'parent_id' => 'nullable|exists:messages,id'
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'parent_id' => $request->parent_id
        ]);

        return response()->json(
            $message->load('user')
        );
    }

    public function fetchMessages()
    {
        $messages = Message::with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}