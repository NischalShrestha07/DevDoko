<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class PostService
{
    public function createTextPost($user, $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $post = $user->posts()->create([
                'type' => 'text',
                'caption' => $data['caption'],
                'visibility' => $data['visibility'] ?? 'public',
            ]);

            if (!empty($data['tags'])) {
                $tagIds = collect($data['tags'])->map(function ($tagName) {
                    return Tag::firstOrCreate(['name' => strtolower(trim($tagName))])->id;
                });

                $post->tags()->attach($tagIds);
            }

            return $post;
        });
    }

    public function getFeedForUser($user, $limit = 20)
    {
        $followingIds = $user->following()->pluck('users.id');

        return Post::with(['user.profile', 'tags', 'likes', 'comments.user.profile'])
            ->whereIn('user_id', $followingIds)
            ->orWhere('visibility', 'public')
            ->latest()
            ->paginate($limit);
    }
}
