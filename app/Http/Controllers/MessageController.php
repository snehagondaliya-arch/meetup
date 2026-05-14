<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Organization;
use App\Models\User;
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
            'messageable_id' => $isOrg ? Auth::guard('organization')->id() : Auth::id(),
            'messageable_type' => $isOrg ? Organization::class : User::class,
            'message' => $request->message,
            'parent_id' => $request->parent_id
        ]);

        return response()->json(
             $message->load('messageable')
        );
    }

    public function fetchMessages()
    {
        $messages = Message::with(['messageable',
            'replies.messageable'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}