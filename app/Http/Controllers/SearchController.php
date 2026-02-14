<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
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

        if (!$query) {
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
                ->paginate(10);
        }

        //  Posts Search
        if ($type === 'all' || $type === 'posts') {
            $posts = Post::where('title', 'LIKE', "%{$query}%")
                ->where(function ($q) {
                    $q->where('visibility', 'public')
                        ->orWhere('user_id', Auth::id());
                })
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
