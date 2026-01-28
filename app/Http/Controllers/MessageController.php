<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Display messages inbox
     */
    public function index()
    {
        // Get conversations (users you've messaged or who messaged you)
        $conversations = Message::selectRaw('
            CASE
                WHEN sender_id = ? THEN receiver_id
                ELSE sender_id
            END as other_user_id,
            MAX(created_at) as last_message_at
        ', [Auth::id()])
            ->where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->groupBy('other_user_id')
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Get user details for each conversation
        $conversations = $conversations->map(function ($conversation) {
            $user = User::with('profile')->find($conversation->other_user_id);
            $lastMessage = Message::where(function ($query) use ($user) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $user->id);
            })->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', Auth::id());
            })->latest()->first();

            return [
                'user' => $user,
                'last_message' => $lastMessage,
                'unread_count' => Message::where('sender_id', $user->id)
                    ->where('receiver_id', Auth::id())
                    ->where('read_at', null)
                    ->count()
            ];
        });

        return view('messages.index', compact('conversations'));
    }

    /**
     * Show conversation with a specific user
     */
    public function show(User $user)
    {
        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        // Get messages between users
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $user->id);
        })
            ->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', Auth::id());
            })
            ->with(['sender.profile', 'receiver.profile'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('messages.show', compact('user', 'messages'));
    }

    /**
     * Send a message
     */
    public function store(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'content' => $request->content,
        ]);

        return redirect()->back();
    }
}
