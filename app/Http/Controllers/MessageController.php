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

        $isOrg = Auth::guard('organization')->check();

        $message = Message::create([ 
            'user_id' => $isOrg ? Auth::guard('organization')->id() : Auth::id(),
            'user_type' => $isOrg ? 'organization' : 'user',
            'message' => $request->message,
            'parent_id' => $request->parent_id
        ]);

        return response()->json(
            $message->load(['user', 'organization'])
        );
    }

    public function fetchMessages()
    {
        $messages = Message::with(['user', 'organization', 'replies.user', 'replies.organization'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}