<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role',
        'email_verified_at',
        'is_active',
        'last_login_at',
        'github_id',
        'github_token',
        'github_refresh_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    protected $appends = ['avatar_url'];

    // Relationships
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function saves()
    {
        return $this->hasMany(Save::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
            ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
            ->withTimestamps();
    }

    // Scopes
    public function scopeDevelopers($query)
    {
        return $query->whereHas('profile')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc');
    }

    public function scopePopular($query)
    {
        return $query->withCount('followers')
            ->orderBy('followers_count', 'desc');
    }

    // Accessors
    public function getAvatarUrlAttribute()
    {
        return $this->profile->avatar_url ?? asset('images/default-avatar.png');
    }

    public function getPostsCountAttribute()
    {
        return $this->posts()->count();
    }

    public function getFollowersCountAttribute()
    {
        return $this->followers()->count();
    }

    public function getFollowingCountAttribute()
    {
        return $this->following()->count();
    }

    public function getProjectsCountAttribute()
    {
        return $this->projects()->count();
    }

    // Methods
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function unreadNotificationsCount()
    {
        return $this->notifications()->where('read_at', null)->count();
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Create profile for new user
            $user->profile()->create([
                'username' => strtolower(str_replace(' ', '', $user->name)) . rand(100, 999),
                'bio' => 'Hello! I\'m new to DevDoko.',
                'location' => null,
                'website' => null,
                'github_link' => null,
                'linkedin_link' => null,
                'twitter_link' => null,
                'tech_stack' => []
            ]);
        });
    }
}
