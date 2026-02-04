<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function welcome()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('welcome');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Get posts for feed
        $posts = Post::with(['user.profile', 'likes', 'comments.user.profile', 'tags'])
            ->visibleTo($user)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get suggested users (excluding already followed)
        $suggestedUsers = User::where('id', '!=', $user->id)
            ->whereDoesntHave('followers', function ($query) use ($user) {
                $query->where('follower_id', $user->id);
            })
            ->with('profile')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        // Get trending tags
        $trendingTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        return view('home', compact('posts', 'suggestedUsers', 'trendingTags'));
    }

    public function feed(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'all');

        $posts = Post::with(['user.profile', 'likes', 'comments.user.profile', 'tags'])
            ->visibleTo($user);

        // Filter by type if specified
        if ($type && $type !== 'all') {
            $posts->where('type', $type);
        }

        $posts = $posts->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'posts' => $posts->items(),
                'next_page_url' => $posts->nextPageUrl()
            ]);
        }

        return view('posts.feed', compact('posts'));
    }
}
