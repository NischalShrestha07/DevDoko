<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LikeController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function toggle(Request $request, Post $post)
    {
        $request->validate([
            'type' => ['nullable', 'string', Rule::in(Like::TYPES)],
        ]);

        $user = Auth::user();
        $type = $request->input('type', 'like');

        [$liked, $isNew, $reactionType] = DB::transaction(function () use ($post, $user, $type) {
            $like = $post->likes()->where('user_id', $user->id)->lockForUpdate()->first();

            if (! $like) {
                $post->likes()->create(['user_id' => $user->id, 'type' => $type]);

                return [true, true, $type];
            }

            if ($like->type === $type) {
                $like->delete();

                return [false, false, null];
            }

            $like->update(['type' => $type]);

            return [true, false, $type];
        });

        if ($isNew) {
            $this->notificationService->likeNotification($user, $post, 'post', $reactionType);
        }

        $post->updateLikeCount();

        return response()->json([
            'liked' => $liked,
            'type' => $reactionType,
            'likes_count' => $post->fresh()->likes_count,
        ]);
    }
}
