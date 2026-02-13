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
            ->latest()
            ->paginate(12);

        return view('saved.index', compact('savedPosts'));
    }

    /**
     * Save a post.
     */
    public function store(Post $post)
    {
        $save = Save::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        if ($save->wasRecentlyCreated) {
            return response()->json([
                'success' => true,
                'saved' => true,
                'message' => 'Post saved successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'saved' => false,
            'message' => 'Post already saved!'
        ]);
    }

    /**
     * Unsave a post.
     */
    public function destroy(Post $post)
    {
        $deleted = Save::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'saved' => false,
                'message' => 'Post removed from saved!'
            ]);
        }

        return response()->json([
            'success' => false,
            'saved' => true,
            'message' => 'Post was not saved!'
        ]);
    }
}
