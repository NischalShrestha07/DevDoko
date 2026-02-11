<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'trending');

        // Trending Posts (most liked in last 7 days)
        $trendingPosts = Post::with(['user.profile', 'tags'])
            ->where('visibility', 'public')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->withCount(['likes', 'comments'])
            ->orderByRaw('(likes_count * 2 + comments_count) DESC')
            ->take(12)
            ->get();

        // Latest Posts
        $latestPosts = Post::with(['user.profile', 'tags'])
            ->where('visibility', 'public')
            ->latest()
            ->take(12)
            ->get();

        // Popular Developers (most followers)
        $popularDevelopers = User::with('profile')
            ->whereHas('profile')
            // ->where('is_active', true)
            ->withCount(['followers', 'posts'])
            ->orderBy('followers_count', 'desc')
            ->take(12)
            ->get();

        // Popular Tags
        $popularTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(24)
            ->get();

        // Top Tech Topics
        $techTopics = [
            ['name' => 'Laravel', 'icon' => 'bi-code-slash', 'color' => '#ff2d20', 'count' => Tag::where('name', 'like', '%laravel%')->count()],
            ['name' => 'React', 'icon' => 'bi-braces', 'color' => '#61dafb', 'count' => Tag::where('name', 'like', '%react%')->count()],
            ['name' => 'Vue.js', 'icon' => 'bi-braces-asterisk', 'color' => '#42b883', 'count' => Tag::where('name', 'like', '%vue%')->count()],
            ['name' => 'JavaScript', 'icon' => 'bi-filetype-js', 'color' => '#f7df1e', 'count' => Tag::where('name', 'like', '%javascript%')->count()],
            ['name' => 'Python', 'icon' => 'bi-filetype-py', 'color' => '#3776ab', 'count' => Tag::where('name', 'like', '%python%')->count()],
            ['name' => 'Node.js', 'icon' => 'bi-node-plus', 'color' => '#339933', 'count' => Tag::where('name', 'like', '%node%')->count()],
            ['name' => 'Docker', 'icon' => 'bi-box', 'color' => '#2496ed', 'count' => Tag::where('name', 'like', '%docker%')->count()],
            ['name' => 'AWS', 'icon' => 'bi-cloud', 'color' => '#ff9900', 'count' => Tag::where('name', 'like', '%aws%')->count()],
        ];

        return view('explore.index', compact(
            'trendingPosts',
            'latestPosts',
            'popularDevelopers',
            'popularTags',
            'techTopics',
            'type'
        ));
    }
}
