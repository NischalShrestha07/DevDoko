<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $user = Auth::user();

        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $post->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        // Update like count
        $post->updateLikeCount();

        if (request()->ajax()) {
            return response()->json([
                'liked' => $liked,
                'likes_count' => $post->fresh()->likes_count
            ]);
        }

        return back();
    }
}
