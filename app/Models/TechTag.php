<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TechTag extends Model
{
    protected $fillable = ['name'];

    public function profiles()
    {
        return $this->belongsToMany(Profile::class, 'profile_tech_tag');
    }

    public function getSlugAttribute(): string
    {
        return Str::slug($this->name);
    }
}
