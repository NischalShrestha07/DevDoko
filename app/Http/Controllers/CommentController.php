<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    // Store a new comment
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // Check if user can comment on this post
        if (
            $post->visibility === 'followers' &&
            !$user->isFollowing($post->user) &&
            $user->id !== $post->user_id
        ) {
            return response()->json(['error' => 'Not authorized'], 403);
        }

        $comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => $request->content,
        ]);

        $comment->load('user.profile');

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'html' => view('comments.partial', compact('comment'))->render(),
        ]);
    }

    // Delete a comment
    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Not authorized'], 403);
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }
}
