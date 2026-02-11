<?php
// app/Models/Message.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'type',
        'code_snippet',
        'code_language',
        'file_path',
        'file_name',
        'file_size',
        'read_at',
        'delivered_at',
        'deleted_for_sender_at',
        'deleted_for_receiver_at',
        'reply_to_id',
        'is_thread_start',
        'reactions',
        'is_starred_by_sender',
        'is_starred_by_receiver'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'delivered_at' => 'datetime',
        'deleted_for_sender_at' => 'datetime',
        'deleted_for_receiver_at' => 'datetime',
        'is_thread_start' => 'boolean',
        'is_starred_by_sender' => 'boolean',
        'is_starred_by_receiver' => 'boolean',
        'reactions' => 'array',
    ];

    protected $appends = ['file_url', 'time_ago', 'is_code', 'reaction_summary'];

    // Relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'reply_to_id');
    }

    public function reactions()
    {
        return $this->hasMany(MessageReaction::class);
    }

    // Scopes
    public function scopeUnread($query, $userId)
    {
        return $query->where('receiver_id', $userId)
            ->whereNull('read_at')
            ->whereNull('deleted_for_receiver_at');
    }

    public function scopeStarred($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
                ->where('is_starred_by_sender', true)
                ->orWhere('receiver_id', $userId)
                ->where('is_starred_by_receiver', true);
        });
    }

    public function scopeCodeSnippets($query)
    {
        return $query->where('type', 'code');
    }

    // Accessors
    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIsCodeAttribute()
    {
        return $this->type === 'code';
    }

    public function getReactionSummaryAttribute()
    {
        return $this->reactions()
            ->select('reaction', \DB::raw('count(*) as count'))
            ->groupBy('reaction')
            ->get()
            ->pluck('count', 'reaction')
            ->toArray();
    }

    // Methods
    public function markAsDelivered()
    {
        if (!$this->delivered_at) {
            $this->update(['delivered_at' => now()]);
        }
    }

    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    public function addReaction($userId, $reaction)
    {
        return $this->reactions()->firstOrCreate([
            'user_id' => $userId,
            'reaction' => $reaction
        ]);
    }

    public function removeReaction($userId, $reaction)
    {
        return $this->reactions()
            ->where('user_id', $userId)
            ->where('reaction', $reaction)
            ->delete();
    }

    public function toggleStar($userId)
    {
        if ($userId === $this->sender_id) {
            $this->update(['is_starred_by_sender' => !$this->is_starred_by_sender]);
        } elseif ($userId === $this->receiver_id) {
            $this->update(['is_starred_by_receiver' => !$this->is_starred_by_receiver]);
        }
    }

    public function deleteForUser($userId)
    {
        if ($userId === $this->sender_id) {
            $this->update(['deleted_for_sender_at' => now()]);
        } elseif ($userId === $this->receiver_id) {
            $this->update(['deleted_for_receiver_at' => now()]);
        }

        // Hard delete if both users deleted the message
        if ($this->deleted_for_sender_at && $this->deleted_for_receiver_at) {
            $this->delete();
        }
    }
}
