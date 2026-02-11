<?php
// app/Http/Controllers/MessageController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\MessageReaction;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    /**
     * Display messages inbox with developer-specific features
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get conversations with enhanced data
        $conversations = Message::selectRaw('
            CASE
                WHEN sender_id = ? THEN receiver_id
                ELSE sender_id
            END as other_user_id,
            MAX(created_at) as last_message_at
        ', [$user->id])
            ->where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->whereNull('deleted_for_sender_at')
            ->whereNull('deleted_for_receiver_at')
            ->groupBy('other_user_id')
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Get user details and enhance conversation data
        $conversations = $conversations->map(function ($conversation) use ($user) {
            $otherUser = User::with('profile')->find($conversation->other_user_id);

            if (!$otherUser) return null;

            $lastMessage = Message::where(function ($query) use ($otherUser, $user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $otherUser->id);
            })->orWhere(function ($query) use ($otherUser, $user) {
                $query->where('sender_id', $otherUser->id)
                    ->where('receiver_id', $user->id);
            })
                ->whereNull('deleted_for_sender_at')
                ->whereNull('deleted_for_receiver_at')
                ->latest()
                ->first();

            $unreadCount = Message::where('sender_id', $otherUser->id)
                ->where('receiver_id', $user->id)
                ->whereNull('read_at')
                ->whereNull('deleted_for_receiver_at')
                ->count();

            $codeSnippetCount = Message::where(function ($q) use ($otherUser, $user) {
                $q->where('sender_id', $user->id)->where('receiver_id', $otherUser->id)
                    ->orWhere('sender_id', $otherUser->id)->where('receiver_id', $user->id);
            })
                ->where('type', 'code')
                ->count();

            return [
                'user' => $otherUser,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
                'code_snippet_count' => $codeSnippetCount,
                'is_online' => $otherUser->isOnline(),
                'last_seen' => $otherUser->last_login_at,
            ];
        })->filter();

        // Get starred messages
        $starredMessages = Message::starred($user->id)
            ->with(['sender.profile', 'receiver.profile'])
            ->latest()
            ->limit(10)
            ->get();

        // Get code snippets shared
        $codeSnippets = Message::codeSnippets()
            ->where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->with(['sender.profile', 'receiver.profile'])
            ->latest()
            ->limit(10)
            ->get();

        // Filter by status if requested
        $filter = $request->get('filter', 'all');
        if ($filter === 'unread') {
            $conversations = $conversations->filter(fn($c) => $c['unread_count'] > 0);
        } elseif ($filter === 'code') {
            $conversations = $conversations->filter(fn($c) => $c['code_snippet_count'] > 0);
        }

        return view('messages.index', compact('conversations', 'starredMessages', 'codeSnippets', 'filter'));
    }

    /**
     * Show conversation with enhanced developer features
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();

        // Prevent self-messaging
        if ($user->id === $currentUser->id) {
            return redirect()->route('messages.index')
                ->with('error', 'You cannot message yourself.');
        }

        // Mark received messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $currentUser->id)
            ->whereNull('read_at')
            ->whereNull('deleted_for_receiver_at')
            ->update(['read_at' => now(), 'delivered_at' => now()]);

        // Get messages with all relationships
        $messages = Message::where(function ($query) use ($user, $currentUser) {
            $query->where('sender_id', $currentUser->id)
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user, $currentUser) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', $currentUser->id);
        })
            ->whereNull('deleted_for_sender_at')
            ->whereNull('deleted_for_receiver_at')
            ->with(['sender.profile', 'receiver.profile', 'replyTo', 'reactions.user.profile'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Group messages by date
        $groupedMessages = $messages->groupBy(function ($message) {
            return $message->created_at->format('Y-m-d');
        });

        // Get conversations for sidebar
        $conversations = $this->getConversations();

        // Get suggested code languages
        $codeLanguages = ['php', 'javascript', 'python', 'java', 'csharp', 'ruby', 'go', 'rust', 'typescript', 'html', 'css', 'sql', 'json', 'bash'];

        return view('messages.show', compact('user', 'messages', 'groupedMessages', 'conversations', 'codeLanguages'));
    }

    private function getConversations()
    {
        $user = Auth::user();

        $conversations = Message::selectRaw('
        CASE
            WHEN sender_id = ? THEN receiver_id
            ELSE sender_id
        END as other_user_id,
        MAX(created_at) as last_message_at
    ', [$user->id])
            ->where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->whereNull('deleted_for_sender_at')
            ->whereNull('deleted_for_receiver_at')
            ->groupBy('other_user_id')
            ->orderBy('last_message_at', 'desc')
            ->get();

        return $conversations->map(function ($conversation) use ($user) {
            $otherUser = User::with('profile')->find($conversation->other_user_id);

            if (!$otherUser) return null;

            $lastMessage = Message::where(function ($query) use ($otherUser, $user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $otherUser->id);
            })->orWhere(function ($query) use ($otherUser, $user) {
                $query->where('sender_id', $otherUser->id)
                    ->where('receiver_id', $user->id);
            })
                ->latest()
                ->first();

            $unreadCount = Message::where('sender_id', $otherUser->id)
                ->where('receiver_id', $user->id)
                ->whereNull('read_at')
                ->whereNull('deleted_for_receiver_at')
                ->count();

            return [
                'user' => $otherUser,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount,
                'is_online' => $otherUser->isOnline(),
            ];
        })->filter();
    }

    /**
     * Send a message with support for code, files, etc.
     */
    public function store(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'nullable|string|max:5000',
            'type' => 'required|in:text,code,file',
            'code_snippet' => 'nullable|required_if:type,code|string',
            'code_language' => 'nullable|required_if:type,code|string|max:50',
            'file' => 'nullable|required_if:type,file|file|max:10240', // 10MB max
            'reply_to_id' => 'nullable|exists:messages,id',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $messageData = [
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'type' => $request->type,
            'reply_to_id' => $request->reply_to_id,
            'delivered_at' => now(),
        ];

        // Handle different message types
        switch ($request->type) {
            case 'code':
                $messageData['code_snippet'] = $request->code_snippet;
                $messageData['code_language'] = $request->code_language;
                $messageData['content'] = $request->content ?? 'Shared a code snippet';
                break;

            case 'file':
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $path = $file->store('messages/' . date('Y/m'), 'public');
                    $messageData['file_path'] = $path;
                    $messageData['file_name'] = $file->getClientOriginalName();
                    $messageData['file_size'] = $file->getSize();
                    $messageData['content'] = $request->content ?? $file->getClientOriginalName();
                }
                break;

            default: // text
                $messageData['content'] = $request->content;
                break;
        }

        $message = Message::create($messageData);

        // Create notification for receiver - FIXED
        if ($user->id !== Auth::id()) {
            $senderName = Auth::user()->profile->username ?? Auth::user()->name;

            $user->notifications()->create([
                'from_user_id' => Auth::id(),
                'type' => 'message',
                'message' => $senderName . ' sent you a message',
                'data' => [
                    'message_id' => $message->id,
                    'sender_id' => Auth::id(),
                    'sender_name' => $senderName,
                    'sender_avatar' => Auth::user()->avatar_url,
                    'content' => Str::limit($message->content, 100),
                    'type' => $message->type,
                    'url' => route('messages.show', Auth::user())
                ]
            ]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => view('messages.partials.message', ['message' => $message])->render(),
                'message_id' => $message->id
            ]);
        }

        return redirect()->back();
    }

    /**
     * Add reaction to message
     */
    public function addReaction(Request $request, Message $message)
    {
        $request->validate([
            'reaction' => 'required|string|in:👍,❤️,🎉,🚀,👨‍💻,🔥,⭐,🤔,💡,✅'
        ]);

        // Check if user has permission (must be participant)
        if (!in_array(Auth::id(), [$message->sender_id, $message->receiver_id])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $reaction = $message->addReaction(Auth::id(), $request->reaction);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'reaction' => $reaction,
                'summary' => $message->fresh()->reaction_summary
            ]);
        }

        return redirect()->back();
    }

    /**
     * Remove reaction from message
     */
    public function removeReaction(Request $request, Message $message)
    {
        $request->validate([
            'reaction' => 'required|string'
        ]);

        $message->removeReaction(Auth::id(), $request->reaction);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'summary' => $message->fresh()->reaction_summary
            ]);
        }

        return redirect()->back();
    }

    /**
     * Star/unstar message
     */
    public function toggleStar(Message $message)
    {
        if (!in_array(Auth::id(), [$message->sender_id, $message->receiver_id])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->toggleStar(Auth::id());

        return response()->json([
            'success' => true,
            'starred' => Auth::id() === $message->sender_id
                ? $message->is_starred_by_sender
                : $message->is_starred_by_receiver
        ]);
    }

    /**
     * Delete message for user
     */
    public function destroy(Message $message)
    {
        if (!in_array(Auth::id(), [$message->sender_id, $message->receiver_id])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->deleteForUser(Auth::id());

        return response()->json(['success' => true]);
    }

    /**
     * Mark conversation as read
     */
    public function markAsRead(User $user)
    {
        $updated = Message::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'count' => $updated
        ]);
    }

    /**
     * Get unread count
     */
    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->whereNull('deleted_for_receiver_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Search messages
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $user = Auth::user();

        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
            ->where(function ($q) use ($request) {
                $query = $request->query;

                $q->where('content', 'LIKE', '%' . $query . '%')
                    ->orWhere('code_snippet', 'LIKE', '%' . $query . '%')
                    ->orWhere('file_name', 'LIKE', '%' . $query . '%');
            })
            ->whereNull('deleted_for_sender_at')
            ->whereNull('deleted_for_receiver_at')
            ->with(['sender.profile', 'receiver.profile'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get unique conversations for sidebar
        $conversations = Message::selectRaw('
        CASE
            WHEN sender_id = ? THEN receiver_id
            ELSE sender_id
        END as other_user_id,
        MAX(created_at) as last_message_at
    ', [$user->id])
            ->where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->whereNull('deleted_for_sender_at')
            ->whereNull('deleted_for_receiver_at')
            ->groupBy('other_user_id')
            ->orderBy('last_message_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($conversation) use ($user) {
                $otherUser = User::with('profile')->find($conversation->other_user_id);
                if (!$otherUser) return null;

                $lastMessage = Message::where(function ($query) use ($otherUser, $user) {
                    $query->where('sender_id', $user->id)->where('receiver_id', $otherUser->id)
                        ->orWhere('sender_id', $otherUser->id)->where('receiver_id', $user->id);
                })
                    ->latest()
                    ->first();

                return [
                    'user' => $otherUser,
                    'last_message' => $lastMessage,
                    'unread_count' => Message::where('sender_id', $otherUser->id)
                        ->where('receiver_id', $user->id)
                        ->whereNull('read_at')
                        ->count()
                ];
            })
            ->filter();

        return view('messages.search', compact('messages', 'conversations', 'request'));
    }
}
