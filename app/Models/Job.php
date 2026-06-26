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
}
