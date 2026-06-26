<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

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
        // 'last_login_at',
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
        return $this->hasOne(Profile::class)->withDefault(function ($profile, $user) {
            $profile->forceFill([
                'user_id' => $user->id,
                'username' => $user->username ?? 'user_' . $user->id,
                'bio' => 'Hello! I\'m new to DevDoko.',
                'avatar' => null,
                'github_link' => null,
                'portfolio_link' => null,
                'reputation_score' => 0,
            ]);
        });
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

    public function getAvatarUrlAttribute()
    {
        if ($this->profile) {
            return $this->profile->avatar_url;
        }

        // Return default avatar if no profile
        $name = $this->name ?? $this->username ?? 'User';
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random&color=fff';
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
            $user->profile()->create([
                'username' => strtolower(str_replace(' ', '', $user->name)) . rand(100, 999),
                'bio' => 'Hello! I\'m new to DevDoko.',
                'avatar' => null,
                'github_link' => null,
                'portfolio_link' => null,
                'reputation_score' => 0,
            ]);
        });
    }

    public function isOnline(): bool
    {
        return $this->last_login_at && $this->last_login_at->diffInMinutes(now()) < 5;
    }

    // Also add this scope for active users
    public function scopeActive($query)
    {
        return $query->where('last_login_at', '>=', now()->subMinutes(5));
    }

    public function scopeSuggested($query, $userId)
    {
        return $query->where('id', '!=', $userId)
            ->whereDoesntHave('followers', function ($q) use ($userId) {
                $q->where('follower_id', $userId);
            })
            ->inRandomOrder();
    }

    public function savedPosts()
    {
        return $this->belongsToMany(Post::class, 'saves')
            ->withTimestamps()
            ->orderBy('saves.created_at', 'desc');
    }

    public function marketplaceReviews()
    {
        return $this->hasMany(MarketplaceReview::class, 'seller_id');
    }

    public function savedMarketplaceSearches()
    {
        return $this->hasMany(MarketplaceSavedSearch::class);
    }



    public function savedMarketplaceListings()
    {
        return $this->belongsToMany(MarketplaceListing::class, 'marketplace_saved_listings', 'user_id', 'listing_id')
            ->withTimestamps();
    }

    public function marketplaceListings()
    {
        return $this->hasMany(MarketplaceListing::class);
    }

    public function marketplaceInterests()
    {
        return $this->hasMany(MarketplaceInterest::class);
    }
}
