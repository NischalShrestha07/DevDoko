<?php

// app/Http/Controllers/NotificationController.php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display notifications with filtering
     */
    public function index(Request $request)
    {
        $query = Auth::user()->notifications()
            ->with('fromUser.profile');

        // Filter by type (support grouped types)
        if ($request->has('type') && ! empty($request->type)) {
            $typeMap = [
                'like' => ['like', 'post_like', 'comment_like'],
                'comment' => ['comment', 'reply'],
                'follow' => ['follow'],
                'message' => ['message'],
                'mention' => ['mention'],
                'share' => ['share', 'post_shared'],
                'new_post' => ['new_post'],
            ];
            $types = $typeMap[$request->type] ?? [$request->type];
            $query->whereIn('type', $types);
        }

        // Get notifications
        $notifications = $query->latest()->paginate(20);

        // Mark only the notifications actually shown (respecting the type filter) as read
        Auth::user()->notifications()
            ->whereIn('id', $notifications->pluck('id'))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()->unread()->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    /**
     * Delete a notification
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted');
    }

    /**
     * Get unread count for AJAX
     */
    public function count()
    {
        $count = Auth::user()->notifications()->unread()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
