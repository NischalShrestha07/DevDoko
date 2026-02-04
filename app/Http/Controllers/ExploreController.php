<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index()
    {
        // Simple trending posts
        $trendingPosts = Post::with(['user.profile', 'media'])
            ->where('visibility', 'public')
            ->latest()
            ->take(9)
            ->get();

        // Popular developers
        $popularDevelopers = User::with('profile')
            ->whereHas('posts')
            ->inRandomOrder()
            ->take(12)
            ->get();

        // Popular tags
        $popularTags = Tag::inRandomOrder()
            ->take(20)
            ->get();

        return view('explore.index', compact(
            'trendingPosts',
            'popularDevelopers',
            'popularTags'
        ));
    }
}
