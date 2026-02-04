<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['post_id', 'file_path', 'media_type'];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
