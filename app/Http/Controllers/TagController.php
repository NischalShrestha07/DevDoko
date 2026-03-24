<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show($tag)
    {
        $tag = Tag::where('name', $tag)->firstOrFail();

        $posts = Post::with(['user.profile', 'likes', 'comments'])
            ->whereHas('tags', function ($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->where('visibility', 'public')
            ->latest()
            ->paginate(12);

        return view('tags.show', compact('tag', 'posts'));
    }

    public function trending()
    {        // Trending Posts (most liked in last 7 days)
        $trendingTags = Tag::with(['user.profile', 'tags'])
            ->where('visibility', 'public')
            ->where('created_at', '>=', Carbon::now()->subDays(7))->take(12)
            ->get();

        return view('tags.show', compact('trendingTags'));
    }
}
