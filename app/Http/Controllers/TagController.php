<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    public function show(Tag $tag)
    {
        $posts = Post::with(['user.profile', 'likes', 'comments'])
            ->whereHas('tags', function ($query) use ($tag) {
                $query->where('tags.id', $tag->id);
            })
            ->where('visibility', 'public')
            ->latest()
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
        $tag = (object) ['name' => 'Trending'];

        $posts = Post::with(['user.profile', 'likes', 'comments'])
            ->where('visibility', 'public')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->paginate(12);

        return view('tags.show', compact('tag', 'posts'));
    }
}
