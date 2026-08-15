<?php

// app/Http/Controllers/MessageController.php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

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
            ->visibleTo($user->id)
            ->groupBy('other_user_id')
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Get user details and enhance conversation data (batched, no N+1)
        $conversations = $this->hydrateConversations($conversations, $user, withCodeCount: true);

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
            $conversations = $conversations->filter(fn ($c) => $c['unread_count'] > 0);
        } elseif ($filter === 'code') {
            $conversations = $conversations->filter(fn ($c) => $c['code_snippet_count'] > 0);
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
            ->visibleTo($currentUser->id)
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
            ->visibleTo($user->id)
            ->groupBy('other_user_id')
            ->orderBy('last_message_at', 'desc')
            ->get();

        return $this->hydrateConversations($conversations, $user);
    }

    /**
     * Batch-hydrate raw conversation rows (other_user_id, last_message_at) into full
     * conversation data — one query per aggregate across all rows, not one per row.
     */
    private function hydrateConversations($conversationRows, User $user, bool $withCodeCount = false)
    {
        $otherIds = $conversationRows->pluck('other_user_id')->filter()->unique()->values();

        if ($otherIds->isEmpty()) {
            return collect();
        }

        $users = User::with('profile')->whereIn('id', $otherIds)->get()->keyBy('id');

        $lastMessages = Message::where(function ($q) use ($user, $otherIds) {
            $q->where('sender_id', $user->id)->whereIn('receiver_id', $otherIds)
                ->orWhere(function ($q2) use ($user, $otherIds) {
                    $q2->whereIn('sender_id', $otherIds)->where('receiver_id', $user->id);
                });
        })
            ->visibleTo($user->id)
            ->orderByDesc('created_at')
            ->get()
            ->groupBy(fn ($m) => $m->sender_id === $user->id ? $m->receiver_id : $m->sender_id)
            ->map(fn ($group) => $group->first());

        $unreadCounts = Message::whereIn('sender_id', $otherIds)
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->whereNull('deleted_for_receiver_at')
            ->selectRaw('sender_id, COUNT(*) as cnt')
            ->groupBy('sender_id')
            ->pluck('cnt', 'sender_id');

        $codeSnippetCounts = collect();
        if ($withCodeCount) {
            $codeSnippetCounts = Message::selectRaw('
                CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END as other_user_id,
                COUNT(*) as cnt
            ', [$user->id])
                ->where(function ($q) use ($user, $otherIds) {
                    $q->where('sender_id', $user->id)->whereIn('receiver_id', $otherIds)
                        ->orWhere(function ($q2) use ($user, $otherIds) {
                            $q2->whereIn('sender_id', $otherIds)->where('receiver_id', $user->id);
                        });
                })
                ->where('type', 'code')
                ->groupBy('other_user_id')
                ->pluck('cnt', 'other_user_id');
        }

        return $conversationRows->map(function ($conversation) use ($users, $lastMessages, $unreadCounts, $codeSnippetCounts, $withCodeCount) {
            $otherUser = $users->get($conversation->other_user_id);

            if (! $otherUser) {
                return null;
            }

            $data = [
                'user' => $otherUser,
                'last_message' => $lastMessages->get($otherUser->id),
                'unread_count' => $unreadCounts->get($otherUser->id, 0),
                'is_online' => $otherUser->isOnline(),
            ];

            if ($withCodeCount) {
                $data['code_snippet_count'] = $codeSnippetCounts->get($otherUser->id, 0);
                $data['last_seen'] = $otherUser->last_login_at;
            }

            return $data;
        })->filter();
    }

    /**
     * Send a message with support for code, files, etc.
     */
    public function store(Request $request, User $user)
    {
        if (Auth::user()->isBlockedEitherWay($user)) {
            return $request->expectsJson() || $request->ajax()
                ? response()->json(['error' => 'You cannot message this user.'], 403)
                : back()->with('error', 'You cannot message this user.');
        }

        $validator = Validator::make($request->all(), [
            'content' => 'nullable|string|max:5000',
            'type' => 'required|in:text,code,file',
            'code_snippet' => 'nullable|required_if:type,code|string',
            'code_language' => 'nullable|required_if:type,code|string|max:50',
            'file' => 'nullable|required_if:type,file|file|max:200000', // 10MB max
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
                    $path = $file->store('messages/'.date('Y/m'), 'public');
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

        // Send notification
        $this->notificationService->messageNotification(
            Auth::user(),
            $user,
            $request->content,
            $request->type ?? 'text'
        );
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => view('messages.partials.message', ['message' => $message])->render(),
                'message_id' => $message->id,
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
            'reaction' => 'required|string|in:👍,❤️,🎉,🚀,👨‍💻,🔥,⭐,🤔,💡,✅',
        ]);

        // Check if user has permission (must be participant)
        if (! in_array(Auth::id(), [$message->sender_id, $message->receiver_id])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $reaction = $message->addReaction(Auth::id(), $request->reaction);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'reaction' => $reaction,
                'summary' => $message->load('reactions')->reaction_summary,
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
            'reaction' => 'required|string',
        ]);

        $message->removeReaction(Auth::id(), $request->reaction);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'summary' => $message->load('reactions')->reaction_summary,
            ]);
        }

        return redirect()->back();
    }

    /**
     * Star/unstar message
     */
    public function toggleStar(Message $message)
    {
        if (! in_array(Auth::id(), [$message->sender_id, $message->receiver_id])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->toggleStar(Auth::id());

        return response()->json([
            'success' => true,
            'starred' => Auth::id() === $message->sender_id
                ? $message->is_starred_by_sender
                : $message->is_starred_by_receiver,
        ]);
    }

    /**
     * Delete message for user
     */
    public function destroy(Message $message)
    {
        if (! in_array(Auth::id(), [$message->sender_id, $message->receiver_id])) {
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
            'count' => $updated,
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
            'query' => 'required|string|min:2',
        ]);

        $user = Auth::user();

        $messages = Message::where(function ($q) use ($user) {
            $q->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
            ->where(function ($q) use ($request) {
                $query = $request->query('query');

                $q->where('content', 'LIKE', '%'.$query.'%')
                    ->orWhere('code_snippet', 'LIKE', '%'.$query.'%')
                    ->orWhere('file_name', 'LIKE', '%'.$query.'%');
            })
            ->visibleTo($user->id)
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
            ->visibleTo($user->id)
            ->groupBy('other_user_id')
            ->orderBy('last_message_at', 'desc')
            ->limit(5)
            ->get();

        $conversations = $this->hydrateConversations($conversations, $user);

        return view('messages.search', compact('messages', 'conversations', 'request'));
    }
}
