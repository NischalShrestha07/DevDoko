<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'content',
        'likes_count',
        'replies_count'
    ];

    protected $with = ['user.profile'];

    protected $appends = ['is_liked', 'time_ago'];

    // Relationship with user
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with post
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    // Relationship with parent comment
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Relationship with replies
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at');
    }

    // Relationship with likes
    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class);
    }

    // Check if comment is liked by current user
    public function getIsLikedAttribute(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        return $this->likes()->where('user_id', Auth::id())->exists();
    }

    // Get human readable time
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // Increment likes count
    public function incrementLikes(): void
    {
        $this->increment('likes_count');
    }

    // Decrement likes count
    public function decrementLikes(): void
    {
        $this->decrement('likes_count');
    }

    // Increment replies count
    public function incrementReplies(): void
    {
        $this->increment('replies_count');
    }

    // Decrement replies count
    public function decrementReplies(): void
    {
        $this->decrement('replies_count');
    }

    // Check if user can edit comment
    public function canEdit(User $user): bool
    {
        return $user->id === $this->user_id || $user->is_admin;
    }

    // Check if user can delete comment
    public function canDelete(User $user): bool
    {
        return $user->id === $this->user_id || $user->is_admin || $user->id === $this->post->user_id;
    }
}
