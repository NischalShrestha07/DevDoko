<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Toggle like on a post
    public function toggle(Post $post)
    {
        $user = Auth::user();

        // Check if user can like this post
        if (
            $post->visibility === 'followers' &&
            !$user->isFollowing($post->user) &&
            $user->id !== $post->user_id
        ) {
            return response()->json(['error' => 'Not authorized'], 403);
        }

        $existingLike = $post->likes()->where('user_id', $user->id)->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
            $liked = true;
        }

        $likesCount = $post->likes()->count();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }
}
