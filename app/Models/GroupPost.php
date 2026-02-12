<?php
// app/Models/GroupPost.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GroupPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_id',
        'user_id',
        'post_id',
        'title',
        'content',
        'type',
        'attachments',
        'metadata',
        'likes_count',
        'comments_count',
        'is_pinned',
        'is_important',
        'pinned_until',
    ];

    protected $casts = [
        'attachments' => 'array',
        'metadata' => 'array',
        'is_pinned' => 'boolean',
        'is_important' => 'boolean',
        'pinned_until' => 'datetime',
    ];

    protected $appends = [
        'is_liked',
        'excerpt',
        'formatted_date',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function getIsLikedAttribute()
    {
        if (!Auth::check()) return false;
        return $this->likes()->where('user_id', auth()->id())->exists();
    }

    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getTypeIconAttribute()
    {
        return match ($this->type) {
            'announcement' => '📢',
            'question' => '❓',
            'resource' => '📚',
            'event' => '📅',
            'job' => '💼',
            default => '📝',
        };
    }

    // In app/Models/GroupPost.php

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(GroupPostLike::class, 'group_post_id');
    }

    public function comments()
    {
        return $this->hasMany(GroupPostComment::class, 'group_post_id')->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->hasMany(GroupPostComment::class, 'group_post_id');
    }
}
