<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'caption',
        'visibility'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function codeSnippet()
    {
        return $this->hasOne(CodeSnippet::class);
    }


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function createTextPost(array $data, User $user)
    {
        $post = $this->create([
            'user_id' => $user->id,
            'type' => 'text',
            'caption' => $data['caption'],
            'visibility' => $data['visibility'] ?? 'public',
        ]);

        if (!empty($data['tags'])) {
            $post->attachTags($data['tags']);
        }

        return $post;
    }


    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function saves()
    {
        return $this->hasMany(Save::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function attachTags(array $tagNames)
    {
        $tagIds = [];
        foreach ($tagNames as $tagName) {
            $tag = Tag::firstOrCreate(['name' => strtolower(trim($tagName))]);
            $tagIds[] = $tag->id;
        }
        $this->tags()->sync($tagIds);
    }

    public function like(User $user)
    {
        if (!$this->isLikedBy($user)) {
            $this->likes()->create(['user_id' => $user->id]);
            $this->user->profile->incrementReputation(2, 'like_received');
        }
    }

    public function unlike(User $user)
    {
        $like = $this->likes()->where('user_id', $user->id)->first();
        if ($like) {
            $like->delete();
            $this->user->profile->decrementReputation(2, 'like_removed');
        }
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isSavedBy(User $user)
    {
        return $this->saves()->where('user_id', $user->id)->exists();
    }

    // Scope for feed visibility
    public function scopeVisibleTo($query, User $user = null)
    {
        if ($user) {
            return $query->where(function ($q) use ($user) {
                $q->where('visibility', 'public')
                    ->orWhere(function ($q2) use ($user) {
                        $q2->where('visibility', 'followers')
                            ->whereHas('user.followers', function ($q3) use ($user) {
                                $q3->where('follower_id', $user->id);
                            });
                    });
            });
        }
        return $query->where('visibility', 'public');
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }
}
