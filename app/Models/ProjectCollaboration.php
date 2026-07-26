<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectCollaboration extends Model
{
    protected $fillable = [
        'project_id',
        'user_id',
        'title',
        'description',
        'required_skills',
        'team_size',
        'current_size',
        'timeline',
        'status',
    ];

    protected $casts = [
        'required_skills' => 'array',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
