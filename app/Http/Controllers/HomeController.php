<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth')->except('welcome');
    }

    // Welcome page for guests
    public function welcome()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        $posts = Post::with(['user.profile', 'likes', 'comments'])
            ->where('visibility', 'public')
            ->latest()
            ->take(9)
            ->get();

        return view('welcome', compact('posts'));
    }

    // Home feed for authenticated users
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

        // Get suggested users to follow
        $suggestedUsers = User::whereNotIn('id', $followingIds)
            ->where('id', '!=', $user->id)
            ->with(['profile', 'posts' => function ($query) {
                $query->latest()->take(3);
            }])
            ->whereHas('posts')
            ->inRandomOrder()
            ->limit(5)
            ->get();

        return view('home', compact('posts', 'suggestedUsers'));
    }
}
