<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Save;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaveController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $user = Auth::user();

        $save = Save::firstOrCreate([
            'user_id' => $user->id,
            'post_id' => $post->id
        ]);

        return response()->json([
            'saved' => true,
            'message' => 'Post saved successfully'
        ]);
    }

    public function destroy(Request $request, Post $post)
    {
        $user = Auth::user();

        Save::where('user_id', $user->id)
            ->where('post_id', $post->id)
            ->delete();

        return response()->json([
            'saved' => false,
            'message' => 'Post unsaved successfully'
        ]);
    }

    public function index()
    {
        $user = Auth::user();
        $savedPosts = $user->saves()
            ->with('post.user.profile')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('posts.saved', compact('savedPosts'));
    }
}
