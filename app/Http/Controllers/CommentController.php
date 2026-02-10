<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ]);

        // Update post comment count
        $post->updateCommentCount();

        // Load user relationship for response
        $comment->load('user.profile');

        return response()->json([
            'comment' => $comment,
            'message' => 'Comment added successfully'
        ]);
    }

    public function index(Post $post)
    {
        $comments = $post->comments()
            ->with('user.profile', 'replies.user.profile')
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'html' => view('posts.partials.comments', compact('comments'))->render()
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $comment->update(['content' => $request->content]);

        return back()->with('success', 'Comment updated successfully');
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $post = $comment->post;
        $comment->delete();

        // Update post comment count
        $post->updateCommentCount();

        return response()->json([
            'message' => 'Comment deleted successfully'
        ]);
    }

    public function like(Comment $comment)
    {
        $user = Auth::user();
        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            $comment->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $comment->likes()->count()
        ]);
    }

    public function reply(Request $request, Comment $comment)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $reply = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $comment->post_id,
            'content' => $request->content,
            'parent_id' => $comment->id
        ]);

        // Update post comment count
        $comment->post->updateCommentCount();

        $reply->load('user.profile');

        return response()->json([
            'reply' => $reply,
            'message' => 'Reply added successfully'
        ]);
    }
}
