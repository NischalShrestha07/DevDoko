<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExploreController extends Controller
{
    /**
     * Display explore page
     */
    public function index()
    {
        // Trending posts (most liked in last 7 days)
        $trendingPosts = Post::withCount(['likes', 'comments'])
            ->with(['user.profile', 'tags', 'media'])
            ->where('created_at', '>=', now()->subDays(7))
            ->where('visibility', 'public')
            ->orderByRaw('likes_count + comments_count DESC')
            ->take(9)
            ->get();

        // Popular developers (most followers)
        $popularDevelopers = User::with(['profile', 'followers'])
            ->whereHas('posts')
            ->withCount('followers')
            ->orderBy('followers_count', 'desc')
            ->take(12)
            ->get();

        // Popular tags
        $popularTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(20)
            ->get();

        // Recent posts
        $recentPosts = Post::with(['user.profile', 'tags', 'likes'])
            ->where('visibility', 'public')
            ->latest()
            ->take(12)
            ->get();

        return view('explore.index', compact(
            'trendingPosts',
            'popularDevelopers',
            'popularTags',
            'recentPosts'
        ));
    }
}
