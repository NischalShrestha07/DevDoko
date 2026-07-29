<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Project cover page shown at the site root. Deliberately visible to
     * everyone, including signed-in users, since it is the project's title
     * page; the CTA sends them on to the feed or the landing page.
     */
    public function cover()
    {
        return view('cover');
    }

    public function welcome()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        $stats = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'active_today' => User::where('last_login_at', '>=', Carbon::now()->subDay())->count(),
            'code_snippets' => Post::where('type', 'code')->count(),
        ];

        $featuredPosts = Post::with(['user.profile', 'tags'])
            ->where('visibility', 'public')
            ->latest()
            ->take(6)
            ->get();

        $topDevelopers = User::popular()
            ->with(['profile.techTags'])
            ->take(5)
            ->get();

        return view('welcome', compact('stats', 'featuredPosts', 'topDevelopers'));
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        $posts = Post::with(['user.profile', 'likes', 'saves' => fn ($q) => $q->where('user_id', $user->id), 'comments.user.profile', 'tags'])
            ->visibleTo($user)
            ->latestStable()
            ->paginate(10);

        // Infinite scroll: return just the next batch of rendered cards.
        if ($request->ajax()) {
            return response()->json([
                'html' => $posts->map(fn ($post) => view('posts.partials.card', compact('post'))->render())->implode(''),
                'next_page' => $posts->hasMorePages() ? $posts->currentPage() + 1 : null,
            ]);
        }

        $unreadNotifications = $user->unreadNotificationsCount();

        $userStats = [
            'posts_count' => $user->posts_count,
            'followers_count' => $user->followers_count,
            'following_count' => $user->following_count,
            'likes_received' => Like::whereHas('post', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
        ];

        $trendingPosts = Post::where('created_at', '>=', Carbon::now()->subDays(7))
            ->with('user.profile')
            ->withCount(['likes', 'comments', 'saves'])
            ->orderByRaw('(likes_count * 3 + comments_count * 2 + views_count) DESC')
            ->take(5)
            ->get();

        return view('home', array_merge(
            compact('posts', 'unreadNotifications', 'userStats', 'trendingPosts'),
            $this->sidebarData($user),
        ));
    }

    public function feed(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'all');

        $posts = Post::with(['user.profile', 'likes', 'saves' => fn ($q) => $q->where('user_id', $user->id), 'comments.user.profile', 'tags'])
            ->visibleTo($user);

        if ($type && $type !== 'all') {
            $posts->where('type', $type);
        }

        $posts = $posts->latestStable()
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'posts' => $posts->items(),
                'next_page_url' => $posts->nextPageUrl(),
            ]);
        }

        return view('posts.feed', compact('posts'));
    }

    public function following(Request $request)
    {
        $user = Auth::user();

        $followingIds = $user->following()->pluck('following_id');

        $posts = Post::with(['user.profile', 'likes', 'saves' => fn ($q) => $q->where('user_id', $user->id), 'comments.user.profile', 'tags'])
            ->whereIn('user_id', $followingIds)
            ->visibleTo($user)
            ->latestStable()
            ->paginate(10);

        return view('home', array_merge(
            compact('posts'),
            $this->sidebarData($user),
            ['activeTab' => 'following']
        ));
    }

    public function popular(Request $request)
    {
        $user = Auth::user();

        $posts = Post::with(['user.profile', 'likes', 'saves' => fn ($q) => $q->where('user_id', $user->id), 'comments.user.profile', 'tags'])
            ->visibleTo($user)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->withCount(['likes', 'comments', 'saves'])
            ->orderByRaw('(likes_count * 2 + comments_count) DESC')
            ->paginate(10);

        return view('home', array_merge(
            compact('posts'),
            $this->sidebarData($user),
            ['activeTab' => 'popular']
        ));
    }

    public function latest(Request $request)
    {
        $user = Auth::user();

        $posts = Post::with(['user.profile', 'likes', 'saves' => fn ($q) => $q->where('user_id', $user->id), 'comments.user.profile', 'tags'])
            ->visibleTo($user)
            ->latestStable()
            ->paginate(10);

        return view('home', array_merge(
            compact('posts'),
            $this->sidebarData($user),
            ['activeTab' => 'latest']
        ));
    }

    private function sidebarData(User $user): array
    {
        return [
            'suggestedUsers' => User::suggested($user->id)
                ->with(['profile', 'posts' => function ($query) {
                    $query->latest()->take(3);
                }])
                ->limit(8)
                ->get(),
            'trendingTags' => Tag::withCount('posts')
                ->orderBy('posts_count', 'desc')
                ->limit(12)
                ->get(),
        ];
    }
}
