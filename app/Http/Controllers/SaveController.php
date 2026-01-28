<?php

namespace App\Http\Controllers;

use App\Models\Save;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaveController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Display user's saved posts
     */
    public function index()
    {
        $savedPosts = Auth::user()->saves()
            ->with(['post.user.profile', 'post.tags', 'post.likes', 'post.comments'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('saved.index', compact('savedPosts'));
    }

    /**
     * Save a post
     */
    public function store(Post $post)
    {
        if (!$post->isSavedBy(Auth::user())) {
            Save::create([
                'user_id' => Auth::id(),
                'post_id' => $post->id,
            ]);
        }

        return redirect()->back()->with('success', 'Post saved!');
    }

    /**
     * Unsave a post
     */
    public function destroy(Post $post)
    {
        $save = $post->saves()->where('user_id', Auth::id())->first();

        if ($save) {
            $save->delete();
        }

        return redirect()->back()->with('success', 'Post removed from saves!');
    }
}
