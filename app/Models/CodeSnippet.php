<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeSnippet extends Model
{
    protected $fillable = ['post_id', 'language', 'code'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
