<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Story extends Model
{
    protected $fillable = [
        'user_id',
        'media_path',
        'media_type',
        'caption',
        'views_count',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'views_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(StoryView::class);
    }

    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    public function isViewedBy(User $user): bool
    {
        return $this->views()->where('user_id', $user->id)->exists();
    }

    public function getMediaUrlAttribute(): string
    {
        return Storage::url($this->media_path);
    }
}
