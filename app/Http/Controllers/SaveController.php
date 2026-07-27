<?php
// app/Http/Controllers/SaveController.php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Save;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaveController extends Controller
{
    /**
     * Display saved posts for the authenticated user.
     */
    public function index()
    {
        $savedPosts = Auth::user()->savedPosts()
            ->with(['likes', 'comments', 'tags', 'media', 'user.profile'])
            ->withCount(['likes', 'comments', 'saves'])
            ->latestStable()
            ->paginate(12);

        return view('saved.index', compact('savedPosts'));
    }

    /**
     * Save a post.
     */
    public function store(Request $request, Post $post)
    {
        Save::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'saved' => true,
                'message' => 'Post saved successfully!'
            ]);
        }

        return back()->with('success', 'Post saved successfully!');
    }

    /**
     * Unsave a post.
     */
    public function destroy(Request $request, Post $post)
    {
        Save::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'saved' => false,
                'message' => 'Post removed from saved!'
            ]);
        }

        return back()->with('success', 'Post removed from saved!');
    }
}
