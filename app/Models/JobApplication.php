<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class JobApplication extends Model
{
    protected $fillable = [
        'job_id',
        'user_id',
        'cover_letter',
        'resume_path',
        'answers',
        'status',
    ];

    protected $casts = [
        'answers' => 'array',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getResumeUrlAttribute(): ?string
    {
        return $this->resume_path ? Storage::url($this->resume_path) : null;
    }
}
