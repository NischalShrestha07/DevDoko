<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:5000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        // Update post comment count
        $post->updateCommentCount();

        // Send notification
        $this->notificationService->commentNotification(Auth::user(), $comment, $post);

        // Load user relationship for response
        $comment->load('user.profile');

        if ($request->expectsJson()) {
            $html = view('posts.partials.comment', compact('comment'))->render();

            return response()->json([
                'html' => $html,
                'comment' => $comment,
                'message' => 'Comment added successfully',
            ]);
        }

        return redirect()->back()->with('success', 'Comment added successfully');
    }

    public function index(Post $post)
    {
        $comments = $post->comments()
            ->with('user.profile', 'replies.user.profile')
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $html = $comments->map(fn ($comment) => view('posts.partials.comment', compact('comment'))->render())->implode('');

        return response()->json([
            'html' => $html,
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
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

        return back()->with('success', 'Comment deleted successfully');
    }

    public function like(Request $request, Comment $comment)
    {
        $user = Auth::user();
        $like = $comment->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $liked = false;
            $comment->decrementLikes();
        } else {
            $comment->likes()->create(['user_id' => $user->id]);
            $liked = true;
            $comment->incrementLikes();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'liked' => $liked,
                'likes_count' => $comment->fresh()->likes_count,
            ]);
        }

        return redirect()->back();
    }

    public function reply(Request $request, Comment $comment)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $reply = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $comment->post_id,
            'content' => $request->content,
            'parent_id' => $comment->id,
        ]);

        // Update post comment count
        $comment->post->updateCommentCount();

        // Send notification
        $this->notificationService->replyNotification(Auth::user(), $reply, $comment);

        $reply->load('user.profile');

        $html = view('posts.partials.comment', ['comment' => $reply])->render();

        return response()->json([
            'html' => $html,
            'reply' => $reply,
            'message' => 'Reply added successfully',
        ]);
    }
}
