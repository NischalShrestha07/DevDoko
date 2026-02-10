<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Tag;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function welcome()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        // Get platform statistics
        $stats = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'active_today' => User::where('updated_at', '>=', Carbon::now()->subDay())->count(),
            'code_snippets' => Post::where('type', 'code')->count(),
        ];

        // Get featured content for non-logged in users
        $featuredPosts = Post::with(['user.profile', 'tags'])
            ->where('visibility', 'public')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $topDevelopers = User::withCount(['posts', 'followers'])
            ->orderBy('followers_count', 'desc')
            ->take(5)
            ->get();

        return view('welcome', compact('stats', 'featuredPosts', 'topDevelopers'));
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Get posts for feed with better organization
        $posts = Post::with(['user.profile', 'likes', 'comments.user.profile', 'tags'])
            ->visibleTo($user)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get suggested users based on interests
        $suggestedUsers = User::where('id', '!=', $user->id)
            ->whereDoesntHave('followers', function ($query) use ($user) {
                $query->where('follower_id', $user->id);
            })
            ->with(['profile', 'posts' => function ($query) {
                $query->latest()->take(3);
            }])
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // Get trending tags with post counts
        // $trendingTags = Tag::withCount(['posts' => function ($query) {
        //     $query->where('created_at', '>=', Carbon::now()->subWeek());
        // }])
        $trendingTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(12)
            ->get();

        // Get user's unread notifications count
        $unreadNotifications = $user->notifications()->where('read_at', null)->count();

        // Get user's stats
        $userStats = [
            'posts_count' => $user->posts()->count(),
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
            'likes_received' => DB::table('likes')
                ->join('posts', 'likes.post_id', '=', 'posts.id')
                ->where('posts.user_id', $user->id)
                ->count(),
        ];

        // Get trending posts
        $trendingPosts = Post::where('created_at', '>=', Carbon::now()->subDays(3))
            ->with('user.profile')
            // ->withCount(['likes', 'comments', 'views'])
            ->withCount(['likes', 'comments'])
            ->orderByRaw('(likes_count * 3 + comments_count * 2 + views_count) DESC')
            ->take(5)
            ->get();

        return view('home', compact(
            'posts',
            'suggestedUsers',
            'trendingTags',
            'unreadNotifications',
            'userStats',
            'trendingPosts'
        ));
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
    // Add these methods to your HomeController class

    public function following(Request $request)
    {
        $user = Auth::user();

        // Get posts from users the current user follows
        $posts = Post::with(['user.profile', 'likes', 'comments.user.profile', 'tags'])
            ->whereIn('user_id', $user->following()->pluck('following_id'))
            ->visibleTo($user)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get suggested users based on interests
        $suggestedUsers = User::where('id', '!=', $user->id)
            ->whereDoesntHave('followers', function ($query) use ($user) {
                $query->where('follower_id', $user->id);
            })
            ->with(['profile', 'posts' => function ($query) {
                $query->latest()->take(3);
            }])
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // Get trending tags
        $trendingTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(12)
            ->get();

        return view('home', compact('posts', 'suggestedUsers', 'trendingTags'))
            ->with('activeTab', 'following');
    }

    public function popular(Request $request)
    {
        $user = Auth::user();

        // Get popular posts (based on likes, comments, and recency)
        $posts = Post::with(['user.profile', 'likes', 'comments.user.profile', 'tags'])
            ->visibleTo($user)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->withCount(['likes', 'comments'])
            ->orderByRaw('(likes_count * 2 + comments_count) DESC')
            ->paginate(10);

        // Get suggested users based on interests
        $suggestedUsers = User::where('id', '!=', $user->id)
            ->whereDoesntHave('followers', function ($query) use ($user) {
                $query->where('follower_id', $user->id);
            })
            ->with(['profile', 'posts' => function ($query) {
                $query->latest()->take(3);
            }])
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // Get trending tags
        $trendingTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(12)
            ->get();

        return view('home', compact('posts', 'suggestedUsers', 'trendingTags'))
            ->with('activeTab', 'popular');
    }

    public function latest(Request $request)
    {
        $user = Auth::user();

        // Get latest posts
        $posts = Post::with(['user.profile', 'likes', 'comments.user.profile', 'tags'])
            ->visibleTo($user)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get suggested users based on interests
        $suggestedUsers = User::where('id', '!=', $user->id)
            ->whereDoesntHave('followers', function ($query) use ($user) {
                $query->where('follower_id', $user->id);
            })
            ->with(['profile', 'posts' => function ($query) {
                $query->latest()->take(3);
            }])
            ->inRandomOrder()
            ->limit(8)
            ->get();

        // Get trending tags
        $trendingTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(12)
            ->get();

        return view('home', compact('posts', 'suggestedUsers', 'trendingTags'))
            ->with('activeTab', 'latest');
    }
}
