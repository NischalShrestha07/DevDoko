<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\TechTag;

class TagController extends Controller
{
    public function show(Tag $tag)
    {
        $posts = Post::with(['user.profile', 'likes', 'comments'])
            ->whereHas('tags', function ($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->where('visibility', 'public')
            ->latestStable()
            ->paginate(12);

        return view('tags.show', compact('tag', 'posts'));
    }

    public function techShow($technology)
    {
        $tag = Tag::where('name', $technology)->orWhere('slug', $technology)->first();

        if ($tag) {
            return redirect()->route('tags.show', $tag);
        }

        abort(404);
    }

    public function trending()
    {
        $techTags = TechTag::withCount('profiles')
            ->orderByDesc('profiles_count')
            ->paginate(20);

        return view('tags.trending', compact('techTags'));
    }
}
