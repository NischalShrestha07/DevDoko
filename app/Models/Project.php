<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'short_description',
        'repository_url',
        'live_url',
        'technologies',
        'category',
        'difficulty',
        'is_public',
        'is_featured',
        'status',
        'thumbnail_path',
        'views_count',
        'forks_count',
        'likes_count'
    ];

    protected $casts = [
        'technologies' => 'array',
        'is_public' => 'boolean',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'forks_count' => 'integer',
        'likes_count' => 'integer'
    ];

    protected $appends = ['thumbnail_url'];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function collaborations(): HasMany
    {
        return $this->hasMany(Collaboration::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ProjectLike::class);
    }

    public function forks(): HasMany
    {
        return $this->hasMany(Project::class, 'forked_from_id');
    }

    public function forkedFrom(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'forked_from_id');
    }

    public function contributors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_contributors')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByTechnology($query, $technology)
    {
        return $query->whereJsonContains('technologies', $technology);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('likes_count', 'desc')
            ->orderBy('forks_count', 'desc')
            ->orderBy('views_count', 'desc');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Accessors & Mutators
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return Storage::url($this->thumbnail_path);
        }

        // Generate placeholder based on first technology
        $tech = $this->technologies[0] ?? 'code';
        $colors = [
            'javascript' => '#f7df1e',
            'python' => '#3776ab',
            'react' => '#61dafb',
            'vue' => '#4fc08d',
            'nodejs' => '#339933',
            'php' => '#777bb4',
            'laravel' => '#ff2d20',
            'code' => '#0095f6'
        ];

        $color = $colors[strtolower($tech)] ?? '#0095f6';
        return "https://ui-avatars.com/api/?name=" . urlencode($this->title) . "&background=" . substr($color, 1) . "&color=fff&size=256";
    }

    public function getExcerptAttribute(): string
    {
        return str_limit(strip_tags($this->description), 150);
    }

    public function getTechBadgesAttribute(): array
    {
        return array_slice($this->technologies ?? [], 0, 5);
    }

    public function isLikedBy(User $user): bool
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isContributor(User $user): bool
    {
        return $this->contributors()->where('user_id', $user->id)->exists();
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function incrementLikes()
    {
        $this->increment('likes_count');
    }

    public function decrementLikes()
    {
        $this->decrement('likes_count');
    }

    public function fork(User $user): Project
    {
        $forkedProject = $this->replicate();
        $forkedProject->user_id = $user->id;
        $forkedProject->forked_from_id = $this->id;
        $forkedProject->forks_count = 0;
        $forkedProject->likes_count = 0;
        $forkedProject->views_count = 0;
        $forkedProject->is_featured = false;
        $forkedProject->save();

        $this->increment('forks_count');

        return $forkedProject;
    }
}
