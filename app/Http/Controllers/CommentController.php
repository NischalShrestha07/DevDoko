<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        $comments = $post->comments()
            ->with(['user.profile', 'replies.user.profile', 'likes'])
            ->paginate(10);

        return response()->json($comments);
    }

    public function store(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'parent_id' => $request->parent_id,
            'content' => $request->content
        ]);

        // Increment comment count on post
        $post->increment('comments_count');

        // If this is a reply, increment parent comment's replies count
        if ($request->parent_id) {
            $parentComment = Comment::find($request->parent_id);
            $parentComment->incrementReplies();
        }

        // Load relationships for response
        $comment->load('user.profile');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'html' => view('posts.partials.comment', ['comment' => $comment])->render()
            ]);
        }

        return back()->with('success', 'Comment added successfully!');
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $comment->update(['content' => $request->content]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'comment' => $comment
            ]);
        }

        return back()->with('success', 'Comment updated successfully!');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        // Decrement comment count on post
        $comment->post->decrement('comments_count');

        // If this is a reply, decrement parent comment's replies count
        if ($comment->parent_id) {
            $parentComment = Comment::find($comment->parent_id);
            $parentComment->decrementReplies();
        }

        // Delete all replies first
        $comment->replies()->delete();

        $comment->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Comment deleted successfully!');
    }

    public function like(Comment $comment)
    {
        $user = Auth::user();
        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $comment->decrementLikes();
            $liked = false;
        } else {
            $comment->likes()->create(['user_id' => $user->id]);
            $comment->incrementLikes();
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $comment->likes_count
        ]);
    }

    public function reply(Request $request, Comment $comment)
    {
        return $this->store($request, $comment->post);
    }
}
