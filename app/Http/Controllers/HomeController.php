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

        $stats = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'active_today' => User::where('updated_at', '>=', Carbon::now()->subDay())->count(),
            'code_snippets' => Post::where('type', 'code')->count(),
        ];

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

        $posts = Post::with(['user.profile', 'likes', 'comments.user.profile', 'tags'])
            ->visibleTo($user)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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

        $trendingTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(12)
            ->get();

        $unreadNotifications = $user->notifications()->where('read_at', null)->count();

        $userStats = [
            'posts_count' => $user->posts()->count(),
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
            'likes_received' => DB::table('likes')
                ->join('posts', 'likes.post_id', '=', 'posts.id')
                ->where('posts.user_id', $user->id)
                ->count(),
        ];

        $trendingPosts = Post::where('created_at', '>=', Carbon::now()->subDays(3))
            ->with('user.profile')
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

    public function following(Request $request)
    {
        $user = Auth::user();

        $posts = Post::with(['user.profile', 'likes', 'comments.user.profile', 'tags'])
            ->whereIn('user_id', $user->following()->pluck('following_id'))
            ->visibleTo($user)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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

        $posts = Post::with(['user.profile', 'likes', 'comments.user.profile', 'tags'])
            ->visibleTo($user)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->withCount(['likes', 'comments'])
            ->orderByRaw('(likes_count * 2 + comments_count) DESC')
            ->paginate(10);

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

        $posts = Post::with(['user.profile', 'likes', 'comments.user.profile', 'tags'])
            ->visibleTo($user)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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

        $trendingTags = Tag::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(12)
            ->get();

        return view('home', compact('posts', 'suggestedUsers', 'trendingTags'))
            ->with('activeTab', 'latest');
    }
}
