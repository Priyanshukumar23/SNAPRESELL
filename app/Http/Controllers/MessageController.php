<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index($userId = null)
    {
        $authId = Auth::id();
        
        // Get all users the current user has chatted with
        $chats = Message::where('sender_id', $authId)
            ->orWhere('receiver_id', $authId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->map(function ($message) use ($authId) {
                return $message->sender_id == $authId ? $message->receiver : $message->sender;
            })
            ->unique('id');

        $messages = [];
        $selectedUser = null;

        if ($userId) {
            $selectedUser = User::findOrFail($userId);
            $messages = Message::where(function($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)->where('receiver_id', $userId);
            })->orWhere(function($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $authId);
            })->orderBy('created_at', 'asc')->get();
            
            // Mark as read
            Message::where('sender_id', $userId)
                ->where('receiver_id', $authId)
                ->update(['is_read' => true]);
        }

        return view('messages.index', compact('chats', 'messages', 'selectedUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json($message);
    }
}
