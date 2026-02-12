<?php
// app/Models/GroupEvent.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'title',
        'description',
        'type',
        'format',
        'location',
        'meeting_link',
        'starts_at',
        'ends_at',
        'attendees_count',
        'max_attendees',
        'attendees',
        'waitlist',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'attendees' => 'array',
        'waitlist' => 'array',
    ];

    protected $appends = [
        'is_attending',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsAttendingAttribute()
    {
        if (!auth()->check()) return false;
        $attendees = $this->attendees ?? [];
        return in_array(auth()->id(), $attendees);
    }
}
