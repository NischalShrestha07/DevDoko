<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\TechTag;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function welcome()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        // Get trending posts (developer-focused)
        $posts = Post::with(['user.profile', 'likes', 'comments', 'codeSnippet'])
            ->where('visibility', 'public')
            ->where(function ($query) {
                $query->where('type', 'code')
                    ->orWhereHas('tags', function ($q) {
                        $q->whereIn('name', ['laravel', 'react', 'python', 'javascript', 'php', 'webdev']);
                    });
            })
            ->latest()
            ->take(9)
            ->get();

        // Get popular tech tags
        $techTags = TechTag::withCount('profiles')
            ->orderBy('profiles_count', 'desc')
            ->take(10)
            ->get();

        return view('welcome', compact('posts', 'techTags'));
    }

    public function index()
    {
        $user = Auth::user();

        // Get IDs of users that the current user follows
        $followingIds = $user->following()->pluck('users.id')->toArray();
        $followingIds[] = $user->id; // Include user's own posts

        // Get posts from followed users and public posts
        $posts = Post::with([
            'user.profile',
            'likes' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            },
            'comments' => function ($query) {
                $query->with('user.profile')->latest()->take(3);
            },
            'media',
            'codeSnippet',
            'tags'
        ])
            ->withCount(['likes', 'comments'])
            ->where(function ($query) use ($followingIds, $user) {
                // Posts from followed users
                $query->whereIn('user_id', $followingIds)
                    ->where(function ($q) use ($user) {
                        $q->where('visibility', 'public')
                            ->orWhere('visibility', 'followers');
                    });

                // Public posts from non-followed users (for discovery)
                $query->orWhere(function ($q) use ($followingIds) {
                    $q->where('visibility', 'public')
                        ->whereNotIn('user_id', $followingIds);
                });
            })
            ->latest()
            ->paginate(10);

        // Get suggested users based on tech stack
        $userTechTags = $user->profile->techTags()->pluck('tech_tags.id');

        $suggestedUsers = User::whereNotIn('id', $followingIds)
            ->where('id', '!=', $user->id)
            ->whereHas('profile.techTags', function ($query) use ($userTechTags) {
                $query->whereIn('tech_tags.id', $userTechTags);
            })
            ->with(['profile', 'posts' => function ($query) {
                $query->latest()->take(3);
            }])
            ->whereHas('posts')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        // If not enough suggestions, get random active developers
        if ($suggestedUsers->count() < 3) {
            $additionalUsers = User::whereNotIn('id', $followingIds)
                ->where('id', '!=', $user->id)
                ->with(['profile', 'posts' => function ($query) {
                    $query->latest()->take(3);
                }])
                ->whereHas('posts')
                ->inRandomOrder()
                ->limit(5 - $suggestedUsers->count())
                ->get();

            $suggestedUsers = $suggestedUsers->merge($additionalUsers);
        }

        return view('home', compact('posts', 'suggestedUsers'));
    }

    // Developer Explore Page
    public function explore()
    {
        // Trending code snippets
        $trendingCode = Post::with(['user.profile', 'codeSnippet'])
            ->where('type', 'code')
            ->where('visibility', 'public')
            ->withCount(['likes', 'comments'])
            ->orderByRaw('likes_count + comments_count DESC')
            ->take(6)
            ->get();

        // Popular developers by tech stack
        $user = Auth::user();
        $userTechTags = $user->profile->techTags()->pluck('tech_tags.id');

        $popularDevelopers = User::with(['profile', 'profile.techTags'])
            ->whereHas('posts')
            ->withCount(['posts', 'followers'])
            ->whereHas('profile.techTags', function ($query) use ($userTechTags) {
                $query->whereIn('tech_tags.id', $userTechTags);
            })
            ->orderBy('followers_count', 'desc')
            ->take(12)
            ->get();

        // Popular projects (posts with many interactions)
        $popularProjects = Post::with(['user.profile', 'tags', 'codeSnippet'])
            ->where('visibility', 'public')
            ->withCount(['likes', 'comments'])
            ->orderByRaw('likes_count + comments_count DESC')
            ->take(6)
            ->get();

        return view('explore', compact('trendingCode', 'popularDevelopers', 'popularProjects'));
    }
}
