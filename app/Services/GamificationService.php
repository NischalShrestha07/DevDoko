<?php

namespace App\Services;

use App\Models\User;
use App\Models\Badge;
use App\Models\UserBadge;

class GamificationService
{
    protected $badges = [
        'first_post' => [
            'title' => 'First Post',
            'description' => 'Create your first post',
            'icon' => 'bi-pencil-fill',
            'points' => 10,
        ],
        'code_master' => [
            'title' => 'Code Master',
            'description' => 'Post 10 code snippets',
            'icon' => 'bi-code-slash',
            'points' => 50,
        ],
        'social_butterfly' => [
            'title' => 'Social Butterfly',
            'description' => 'Get 100 followers',
            'icon' => 'bi-people-fill',
            'points' => 100,
        ],
        'helpful_hand' => [
            'title' => 'Helpful Hand',
            'description' => 'Get 50 comments on your posts',
            'icon' => 'bi-chat-heart-fill',
            'points' => 75,
        ],
        'trend_setter' => [
            'title' => 'Trend Setter',
            'description' => 'Have a post with 100+ likes',
            'icon' => 'bi-fire',
            'points' => 150,
        ],
    ];

    public function checkAchievements(User $user)
    {
        $newBadges = [];

        // Check first post
        if ($user->posts()->count() >= 1 && !$user->hasBadge('first_post')) {
            $newBadges[] = $this->awardBadge($user, 'first_post');
        }

        // Check code master
        if ($user->posts()->where('type', 'code')->count() >= 10 && !$user->hasBadge('code_master')) {
            $newBadges[] = $this->awardBadge($user, 'code_master');
        }

        // Check social butterfly
        if ($user->followers()->count() >= 100 && !$user->hasBadge('social_butterfly')) {
            $newBadges[] = $this->awardBadge($user, 'social_butterfly');
        }

        return $newBadges;
    }

    protected function awardBadge(User $user, $badgeKey)
    {
        $badgeData = $this->badges[$badgeKey];

        $badge = Badge::firstOrCreate([
            'key' => $badgeKey,
        ], [
            'title' => $badgeData['title'],
            'description' => $badgeData['description'],
            'icon' => $badgeData['icon'],
            'points' => $badgeData['points'],
        ]);

        UserBadge::create([
            'user_id' => $user->id,
            'badge_id' => $badge->id,
        ]);

        // Add points to reputation
        $user->profile->incrementReputation($badgeData['points'], "badge_{$badgeKey}");

        return $badge;
    }
}
