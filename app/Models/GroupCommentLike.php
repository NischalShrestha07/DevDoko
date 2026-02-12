<?php
// app/Models/GroupCommentLike.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCommentLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_post_comment_id',
        'user_id',
    ];

    protected $table = 'group_comment_likes';

    public function comment()
    {
        return $this->belongsTo(GroupPostComment::class, 'group_post_comment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
