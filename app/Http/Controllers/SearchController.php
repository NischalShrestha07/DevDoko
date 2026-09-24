<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Live-search dropdown: top few users/tags/posts as JSON, for the header search box.
     */
    public function quick(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        if ($query === '') {
            return response()->json(['users' => [], 'tags' => [], 'posts' => []]);
        }

        $users = User::with('profile')
            ->whereHas('profile', fn ($q) => $q->where('username', 'LIKE', "%{$query}%"))
            ->when(Auth::user(), fn ($q) => $q->whereNotIn('id', Auth::user()->hiddenUserIds()))
            ->limit(5)
            ->get()
            ->map(fn ($user) => [
                'username' => $user->profile->username ?? $user->name,
                'name' => $user->name,
                'avatar_url' => $user->avatar_url,
                'url' => route('profile.show', $user->profile->username ?? $user->name),
            ]);

        $tags = Tag::where('name', 'LIKE', "%{$query}%")
            ->withCount('posts')
            ->limit(5)
            ->get()
            ->map(fn ($tag) => [
                'name' => $tag->name,
                'posts_count' => $tag->posts_count,
                'url' => route('tags.show', $tag->slug),
            ]);

        $posts = Post::where(function ($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")->orWhere('content', 'LIKE', "%{$query}%");
        })
            ->visibleTo(Auth::user())
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($post) => [
                'title' => $post->title ?? $post->excerpt,
                'url' => route('posts.show', $post),
            ]);

        return response()->json(compact('users', 'tags', 'posts'));
    }

    /**
     * Main search page with advanced filtering
     */
    public function index(Request $request)
    {
        $query = $request->get('q');
        $language = $request->get('language');
        $type = $request->get('type', 'all');
        $from = $request->get('from');

        // Always define variables to avoid undefined errors
        $users = collect();
        $posts = collect();

        if (! $query) {
            return view('search.index', compact(
                'users',
                'posts',
                'query',
                'type',
                'language',
                'from'
            ));
        }

        if ($type === 'all' || $type === 'developers') {
            $users = User::with('profile')
                ->whereHas('profile', function ($q) use ($query) {
                    $q->where('username', 'LIKE', "%{$query}%");
                })
                ->when(Auth::user(), fn ($q) => $q->whereNotIn('id', Auth::user()->hiddenUserIds()))
                ->paginate(10);
        }

        //  Posts Search
        if ($type === 'all' || $type === 'posts') {
            $posts = Post::where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('content', 'LIKE', "%{$query}%");
            })
                ->visibleTo(Auth::user())
                ->with('user.profile')
                ->paginate(10);
        }

        return view('search.index', compact(
            'users',
            'posts',
            'query',
            'type',
            'language',
            'from'
        ));
    }
}
