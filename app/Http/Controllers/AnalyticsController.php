<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        // Post analytics
        $postStats = Post::where('user_id', $user->id)
            ->selectRaw('
                COUNT(*) as total_posts,
                SUM(CASE WHEN type = "code" THEN 1 ELSE 0 END) as code_posts,
                SUM(CASE WHEN type = "image" THEN 1 ELSE 0 END) as image_posts,
                AVG(LENGTH(caption)) as avg_caption_length
            ')
            ->first();

        // Engagement analytics
        $engagementStats = DB::table('posts')
            ->where('user_id', $user->id)
            ->leftJoin('likes', 'posts.id', '=', 'likes.post_id')
            ->leftJoin('comments', 'posts.id', '=', 'comments.post_id')
            ->selectRaw('
                SUM(likes.id IS NOT NULL) as total_likes,
                SUM(comments.id IS NOT NULL) as total_comments,
                COUNT(DISTINCT likes.user_id) as unique_likers,
                COUNT(DISTINCT comments.user_id) as unique_commenters
            ')
            ->first();

        // Follower growth
        $followerGrowth = DB::table('follows')
            ->where('following_id', $user->id)
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as new_followers
            ')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        // Best performing posts
        $topPosts = Post::where('user_id', $user->id)
            ->withCount(['likes', 'comments'])
            ->orderByRaw('(likes_count + comments_count) DESC')
            ->limit(5)
            ->get();

        return view('analytics.index', compact(
            'postStats',
            'engagementStats',
            'followerGrowth',
            'topPosts'
        ));
    }

    public function postAnalytics($postId)
    {
        $post = Post::where('id', $postId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $analytics = [
            'views' => $post->views ?? 0,
            'likes' => $post->likes()->count(),
            'comments' => $post->comments()->count(),
            'saves' => $post->saves()->count(),
            'shares' => 0, // Implement share tracking
        ];

        // Engagement rate
        $followers = Auth::user()->followers()->count();
        $engagementRate = $followers > 0 ?
            (($analytics['likes'] + $analytics['comments']) / $followers) * 100 : 0;

        // Peak engagement time
        $peakTime = DB::table('likes')
            ->where('post_id', $post->id)
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->first();

        return view('analytics.post', compact('post', 'analytics', 'engagementRate', 'peakTime'));
    }
}
