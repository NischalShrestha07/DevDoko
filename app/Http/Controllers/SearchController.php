<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');

        if (!$query) {
            return view('search.index', [
                'users' => collect(),
                'posts' => collect(),
                'tags' => collect(),
                'query' => '',
            ]);
        }

        // Search users
        $users = User::with('profile')
            ->whereHas('profile', function ($q) use ($query) {
                $q->where('username', 'LIKE', "%{$query}%")
                    ->orWhere('bio', 'LIKE', "%{$query}%");
            })
            ->orWhere('name', 'LIKE', "%{$query}%")
            ->orWhere('email', 'LIKE', "%{$query}%")
            ->paginate(10);


        $posts = Post::where('content', 'LIKE', "%{$query}%")
            ->where(function ($q) {
                $q->where('visibility', 'public')
                    ->orWhere('user_id', Auth::id());
            })
            ->paginate(10);

        // Search tags
        $tags = Tag::where('name', 'LIKE', "%{$query}%")
            ->withCount('posts')
            ->paginate(10);

        return view('search.index', compact('users', 'posts', 'tags', 'query'));
    }

    public function users(Request $request)
    {
        $query = $request->get('q');

        $users = User::with('profile')
            ->whereHas('profile', function ($q) use ($query) {
                $q->where('username', 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'username' => $user->profile->username,
                    'avatar' => $user->profile->avatar_url,
                    'name' => $user->name,
                ];
            });

        return response()->json($users);
    }
}
