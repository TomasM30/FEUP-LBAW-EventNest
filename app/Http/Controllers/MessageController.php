<?php

namespace App\Http\Controllers;
use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function fetchMessages()
    {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        $message = auth()->user()->authenticated->messages()->create([
            'content' => $request->input('content'),
            'id_event' => $request->input('id_event'),
            'date' => now(),
        ]);
    
        broadcast(new MessageSent($message));
        log::info($message);
    
        return ['status' => 'Message Sent!'];
    }
}
