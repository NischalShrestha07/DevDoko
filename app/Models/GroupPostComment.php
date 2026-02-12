<?php
// app/Models/GroupPostComment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPostComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_post_id',
        'user_id',
        'parent_id',
        'content',
        'likes_count',
    ];

    protected $appends = [
        'is_liked',
    ];

    public function post()
    {
        return $this->belongsTo(GroupPost::class, 'group_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(GroupPostComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(GroupPostComment::class, 'parent_id');
    }

    public function likes()
    {
        return $this->hasMany(GroupCommentLike::class, 'group_post_comment_id');
    }

    public function getIsLikedAttribute()
    {
        if (!auth()->check()) return false;
        return $this->likes()->where('user_id', auth()->id())->exists();
    }
}
