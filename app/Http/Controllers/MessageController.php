<?php

namespace App\Http\Controllers;

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
            'messageable_id' => $isOrg
                ? Auth::guard('organization')->id()
                : Auth::id(),

            'messageable_type' => $isOrg
                ? Organization::class
                : User::class,

            'message' => $request->message,

            'parent_id' => $request->parent_id
        ]);

        return response()->json(
            $message->load('messageable')
        );
    }

    public function fetchMessages(Request $request)
    {
        // $afterId = $request->after_id ?? 0;
    
        $messages = Message::with([
                'messageable',
                'replies.messageable'
            ])
            // ->where('id', '>', $afterId)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($messages);
    }
}