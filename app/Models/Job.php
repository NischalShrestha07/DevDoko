<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'jobbs';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'location_type',
        'location',
        'required_skills',
        'company_name',
        'company_logo',
        'company_website',
        'salary_min',
        'salary_max',
        'salary_currency',
        'experience_level',
        'is_featured',
        'is_active',
        'applications_count',
        'views_count',
        'expires_at',
    ];

    protected $casts = [
        'required_skills' => 'array',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function savedBy()
    {
        return $this->hasMany(SavedJob::class);
    }

    public function hasApplied(User $user): bool
    {
        return $this->applications()->where('user_id', $user->id)->exists();
    }

    public function isSavedBy(User $user): bool
    {
        return $this->savedBy()->where('user_id', $user->id)->exists();
    }
}
