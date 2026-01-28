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
        'github_link',
        'portfolio_link',
        'reputation_score',
    ];

    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->username) . '&background=random&color=fff';
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
        $this->reputation_score += $points;
        $this->save();

        // Create reputation log
        \App\Models\ReputationLog::create([
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
