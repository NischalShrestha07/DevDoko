<?php
// app/Models/GroupResourceLike.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupResourceLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_resource_id',
        'user_id',
    ];

    public function resource()
    {
        return $this->belongsTo(GroupResource::class, 'group_resource_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
