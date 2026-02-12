<?php
// app/Models/GroupMember.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupMember extends Pivot
{
    use HasFactory;

    protected $table = 'group_members';

    protected $fillable = [
        'group_id',
        'user_id',
        'role',
        'status',
        'joined_at',
        'approved_at',
        'approved_by',
        'contributions_count',
        'badges',
        'settings',
    ];

    protected $casts = [
        'badges' => 'array',
        'settings' => 'array',
        'joined_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
