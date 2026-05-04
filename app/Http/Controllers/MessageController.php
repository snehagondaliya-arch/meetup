<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'user_id' => Auth::id(),    
            'message' => $request->message
        ]);
        broadcast(new MessageSent($message))->toOthers();
        // return response()->json(['status' => 'Message Sent!']);
        return response()->json($message->load('user'));
    }

    public function fetchMessages()
    {
        return Message::with('user')
        ->orderBy('id', 'asc') 
        ->take(50)
        ->get();
    }


}
