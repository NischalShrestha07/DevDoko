<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'username',
        'bio',
        'avatar',
        'cover_image',
        'github_link',
        'portfolio_link',
        'reputation_score',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    protected $appends = ['avatar_url', 'completeness'];

    /** Rough profile-completion score (0-100) for the "complete your profile" nudge. */
    public function getCompletenessAttribute(): int
    {
        $checks = [
            ! empty($this->avatar),
            ! empty($this->bio),
            $this->relationLoaded('techTags') ? $this->techTags->isNotEmpty() : $this->techTags()->exists(),
            ! empty($this->github_link) || ! empty($this->portfolio_link),
        ];

        return (int) round((array_sum($checks) / count($checks)) * 100);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/'.$this->avatar);
        }

        return 'https://ui-avatars.com/api/?name='
            .urlencode($this->username)
            .'&background=random&color=fff';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function techTags()
    {
        return $this->belongsToMany(TechTag::class, 'profile_tech_tag');
    }

    // Add reputation methods
    public function incrementReputation($points, $action)
    {
        $this->increment('reputation_score', $points);

        // Create reputation log
        ReputationLog::create([
            'user_id' => $this->user_id,
            'action' => $action,
            'points' => $points,
        ]);
    }

    public function decrementReputation($points, $action)
    {
        $this->incrementReputation(-$points, $action);
    }
}
