<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type',
        'code_snippet', // This is a column, not a relationship
        'code_language',
        'image_path',
        'video_path',
        'link_url',
        'link_title',
        'link_description',
        'link_image',
        'visibility',
        'is_pinned',
        'views_count',
        'likes_count',
        'comments_count',
        'shares_count',
        'reading_time',
        'shared_post_id',
        'share_details',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'views_count' => 'integer',
        'likes_count' => 'integer',
        'comments_count' => 'integer',
        'shares_count' => 'integer',
        'reading_time' => 'integer'
    ];

    protected $appends = [
        'excerpt',
        'time_ago',
        'is_liked',
        'is_saved',
        'image_url',
        'formatted_reading_time',
        'type_icon',
        'type_label'
    ];

    // Relationship with user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with likes
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    // Relationship with comments
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc');
    }

    // Relationship with all comments (including replies)
    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    // Relationship with saves/bookmarks
    public function saves(): HasMany
    {
        return $this->hasMany(Save::class);
    }

    // Relationship with tags
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }

    // Relationship with media (for multiple images/videos)
    public function media(): HasMany
    {
        return $this->hasMany(Media::class);
    }

    // Relationship with reports
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    // Accessor for code_snippet (no need for relationship since it's a column)
    public function getCodeSnippetAttribute(): ?string
    {
        return $this->attributes['code_snippet'] ?? null;
    }

    // Check if post is liked by current user
    public function getIsLikedAttribute(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->likes()->where('user_id', Auth::id())->exists();
    }

    // Check if post is saved by current user
    public function getIsSavedAttribute(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->saves()->where('user_id', Auth::id())->exists();
    }

    // Get excerpt for preview
    public function getExcerptAttribute(): string
    {
        $content = strip_tags($this->content ?? '');
        return strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content;
    }

    // Get human readable time
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // Get image URL
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image_path) {
            return Storage::url($this->image_path);
        }
        return null;
    }

    // Get formatted reading time
    public function getFormattedReadingTimeAttribute(): string
    {
        $minutes = $this->reading_time ?? 1;
        return $minutes . ' min read';
    }

    // Get post URL
    public function getUrlAttribute(): string
    {
        return route('posts.show', $this);
    }

    // Calculate reading time
    public function calculateReadingTime(): int
    {
        $wordCount = str_word_count(strip_tags($this->content ?? ''));
        return max(1, ceil($wordCount / 200)); // 200 words per minute
    }

    // Scope for public posts
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    // Scope for visible posts (public + followers)
    public function scopeVisibleTo($query, $user)
    {
        if (!$user) {
            return $query->public();
        }

        return $query->where(function ($q) use ($user) {
            $q->where('visibility', 'public')
                ->orWhere(function ($q2) use ($user) {
                    $q2->where('visibility', 'followers')
                        ->whereIn('user_id', $user->following()->pluck('following_id'));
                })
                ->orWhere('user_id', $user->id);
        });
    }

    // Scope for feed posts
    public function scopeForFeed($query, $user)
    {
        return $query->visibleTo($user)
            ->whereIn('user_id', $user->following()->pluck('following_id')->push($user->id))
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc');
    }

    // Increment views
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    // Update like count
    public function updateLikeCount(): void
    {
        $this->likes_count = $this->likes()->count();
        $this->save();
    }

    // Update comment count
    public function updateCommentCount(): void
    {
        $this->comments_count = $this->allComments()->count();
        $this->save();
    }

    // Helper method to check if user can view post
    public function canView(User $user = null): bool
    {
        if ($this->visibility === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($user->id === $this->user_id) {
            return true;
        }

        if ($this->visibility === 'followers') {
            return $this->user->followers()->where('follower_id', $user->id)->exists();
        }

        return false;
    }

    // Check if user can edit post
    public function canEdit(User $user): bool
    {
        return $user->id === $this->user_id || $user->is_admin;
    }

    // Check if user can delete post
    public function canDelete(User $user): bool
    {
        return $user->id === $this->user_id || $user->is_admin;
    }

    // Get post type icon
    public function getTypeIconAttribute(): string
    {
        return match ($this->type) {
            'code' => 'bi-code-slash',
            'image' => 'bi-image',
            'video' => 'bi-play-circle',
            'link' => 'bi-link-45deg',
            'question' => 'bi-question-circle',
            'project' => 'bi-briefcase',
            'article' => 'bi-file-text',
            'status' => 'bi-chat-dots',
            default => 'bi-file-text'
        };
    }

    // Get post type label
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'code' => 'Code',
            'share' => 'Share',
            'image' => 'Image',
            'video' => 'Video',
            'link' => 'Link',
            'question' => 'Question',
            'project' => 'Project',
            'article' => 'Article',
            'status' => 'Status',
            default => 'Post'
        };
    }

    // Boot method to calculate reading time on save
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            if ($post->isDirty('content')) {
                $post->reading_time = $post->calculateReadingTime();
            }
        });
    }

    public function getVideoUrlAttribute(): ?string
    {
        if ($this->video_path) {
            return Storage::url($this->video_path);
        }
        return null;
    }

    public function sharedPost()
    {
        return $this->belongsTo(Post::class, 'shared_post_id');
    }

    public function shares()
    {
        return $this->hasMany(Post::class, 'shared_post_id');
    }

    // Add accessor for share details
    public function getShareDetailsAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    // Check if post is a share
    public function getIsShareAttribute(): bool
    {
        return $this->type === 'share' || $this->shared_post_id !== null;
    }

    // Get original post (for shares)
    public function getOriginalPostAttribute()
    {
        if ($this->is_share && $this->share_details) {
            // Create a virtual post object from share details
            return (object) $this->share_details;
        }
        return null;
    }
}
