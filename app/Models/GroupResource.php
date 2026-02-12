<?php
// app/Models/GroupResource.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'title',
        'description',
        'type',
        'url',
        'file_path',
        'metadata',
        'tags',
        'downloads_count',
        'likes_count',
        'comments_count',
    ];

    protected $casts = [
        'metadata' => 'array',
        'tags' => 'array',
    ];

    protected $appends = [
        'is_liked',
    ];

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
        return $this->hasMany(GroupResourceLike::class, 'group_resource_id');
    }

    public function getIsLikedAttribute()
    {
        if (!auth()->check()) return false;
        return $this->likes()->where('user_id', auth()->id())->exists();
    }
}
