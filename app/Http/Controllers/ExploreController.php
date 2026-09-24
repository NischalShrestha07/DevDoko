<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'trending');

        // Trending Posts (most liked in last 7 days)
        $trendingPosts = Post::with(['user.profile', 'tags'])
            ->visibleTo($request->user())
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->withCount(['likes', 'comments'])
            ->orderByRaw('(likes_count * 2 + comments_count) DESC')
            ->take(12)
            ->get();

        // Latest Posts
        $latestPosts = Post::with(['user.profile', 'tags'])
            ->visibleTo($request->user())
            ->latest()
            ->take(12)
            ->get();

        // Popular Developers (most followers)
        $popularDevelopers = User::with('profile')
            ->whereHas('profile')
            ->when($request->user(), fn ($q) => $q->whereNotIn('id', $request->user()->hiddenUserIds()))
            ->withCount(['followers', 'posts'])
            ->orderBy('followers_count', 'desc')
            ->take(12)
            ->get();

        // Popular Tags
        $popularTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->take(24)
            ->get();

        // Top Tech Topics (batched into one query)
        $topicKeywords = [
            ['name' => 'Laravel', 'icon' => 'bi-code-slash', 'color' => '#ff2d20', 'keyword' => '%laravel%'],
            ['name' => 'React', 'icon' => 'bi-braces', 'color' => '#61dafb', 'keyword' => '%react%'],
            ['name' => 'Vue.js', 'icon' => 'bi-braces-asterisk', 'color' => '#42b883', 'keyword' => '%vue%'],
            ['name' => 'JavaScript', 'icon' => 'bi-filetype-js', 'color' => '#f7df1e', 'keyword' => '%javascript%'],
            ['name' => 'Python', 'icon' => 'bi-filetype-py', 'color' => '#3776ab', 'keyword' => '%python%'],
            ['name' => 'Node.js', 'icon' => 'bi-node-plus', 'color' => '#339933', 'keyword' => '%node%'],
            ['name' => 'Docker', 'icon' => 'bi-box', 'color' => '#2496ed', 'keyword' => '%docker%'],
            ['name' => 'AWS', 'icon' => 'bi-cloud', 'color' => '#ff9900', 'keyword' => '%aws%'],
        ];

        $topicCounts = Tag::selectRaw("COUNT(*) as count, 
            CASE 
                WHEN name LIKE '%laravel%' THEN '%laravel%'
                WHEN name LIKE '%react%' THEN '%react%'
                WHEN name LIKE '%vue%' THEN '%vue%'
                WHEN name LIKE '%javascript%' THEN '%javascript%'
                WHEN name LIKE '%python%' THEN '%python%'
                WHEN name LIKE '%node%' THEN '%node%'
                WHEN name LIKE '%docker%' THEN '%docker%'
                WHEN name LIKE '%aws%' THEN '%aws%'
            END as keyword")
            ->where(function ($q) {
                $q->where('name', 'like', '%laravel%')
                    ->orWhere('name', 'like', '%react%')
                    ->orWhere('name', 'like', '%vue%')
                    ->orWhere('name', 'like', '%javascript%')
                    ->orWhere('name', 'like', '%python%')
                    ->orWhere('name', 'like', '%node%')
                    ->orWhere('name', 'like', '%docker%')
                    ->orWhere('name', 'like', '%aws%');
            })
            ->groupBy('keyword')
            ->pluck('count', 'keyword');

        $techTopics = array_map(function ($topic) use ($topicCounts) {
            $topic['count'] = $topicCounts[$topic['keyword']] ?? 0;
            $topic['slug'] = Str::slug($topic['name']);

            return $topic;
        }, $topicKeywords);

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
