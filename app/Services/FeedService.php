<?php

namespace App\Services;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class FeedService
{
    public function getPersonalizedFeed(User $user, $limit = 20)
    {
        $followingIds = $user->following()->pluck('users.id')->toArray();
        $followingIds[] = $user->id;

        // Get user's interests (from tech stack, liked posts, etc.)
        $userInterests = $this->getUserInterests($user);

        // Base query
        $query = Post::with([
            'user.profile',
            'tags',
            'likes' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            },
            'comments' => function ($q) {
                $q->with('user.profile')->latest()->take(3);
            },
            'media',
            'codeSnippet'
        ])
            ->withCount(['likes', 'comments'])
            ->where(function ($q) use ($followingIds, $user) {
                // Posts from followed users
                $q->whereIn('user_id', $followingIds)
                    ->where(function ($sq) use ($user) {
                        $sq->where('visibility', 'public')
                            ->orWhere('visibility', 'followers');
                    });

                // Popular posts in user's interests
                $q->orWhere(function ($sq) use ($userInterests) {
                    $sq->where('visibility', 'public')
                        ->whereHas('tags', function ($tsq) use ($userInterests) {
                            $tsq->whereIn('name', $userInterests);
                        });
                });

                // Trending posts (high engagement)
                $q->orWhere(function ($sq) {
                    $sq->where('visibility', 'public')
                        ->where('created_at', '>=', now()->subDays(3))
                        ->has('likes', '>=', 10);
                });
            });

        // Apply scoring algorithm
        $posts = $query->get()->map(function ($post) use ($user, $userInterests) {
            $post->feed_score = $this->calculatePostScore($post, $user, $userInterests);
            return $post;
        })
            ->sortByDesc('feed_score')
            ->take($limit);

        return $posts;
    }

    protected function getUserInterests(User $user)
    {
        $interests = [];

        // From tech stack
        if ($user->profile->techTags) {
            $interests = array_merge($interests, $user->profile->techTags->pluck('name')->toArray());
        }

        // From liked posts' tags
        $likedPostTags = $user->likes()
            ->with('post.tags')
            ->get()
            ->pluck('post.tags')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->toArray();

        $interests = array_merge($interests, $likedPostTags);

        return array_unique($interests);
    }

    protected function calculatePostScore($post, $user, $userInterests)
    {
        $score = 0;

        // 1. Recency (max 30 points)
        $hoursAgo = now()->diffInHours($post->created_at);
        $recencyScore = max(0, 30 - ($hoursAgo * 0.5));
        $score += $recencyScore;

        // 2. Engagement (max 40 points)
        $engagementScore = min(
            40,
            ($post->likes_count * 0.5) +
                ($post->comments_count * 1) +
                ($post->saves_count ?? 0 * 0.3)
        );
        $score += $engagementScore;

        // 3. Relevance to user interests (max 20 points)
        $postTags = $post->tags->pluck('name')->toArray();
        $commonTags = array_intersect($postTags, $userInterests);
        $relevanceScore = count($commonTags) * 2;
        $score += min(20, $relevanceScore);

        // 4. Follow relationship (max 10 points)
        if ($user->isFollowing($post->user)) {
            $score += 10;
        }

        return $score;
    }
}
