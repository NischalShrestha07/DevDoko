<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechTag extends Model
{
    protected $fillable = ['name'];

    public function profiles()
    {
        return $this->belongsToMany(Profile::class, 'profile_tech_tag');
    }
}
