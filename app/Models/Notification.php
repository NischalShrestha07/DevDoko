<?php
// app/Models/Notification.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'from_user_id',
        'type',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    protected $appends = [
        'time_ago'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getActionUrlAttribute(): ?string
    {
        if (!$this->data) {
            return null;
        }

        return match ($this->type) {
            'like', 'comment', 'reply', 'share' => $this->getPostUrl(),
            'post_like', 'post_comment' => $this->getPostUrl(),
            'comment_like' => $this->getCommentUrl(),
            'follow' => $this->getProfileUrl(),
            'mention' => $this->getMentionUrl(),
            'message' => $this->getMessageUrl(),
            'group_invite' => $this->getGroupUrl(),
            'event_reminder' => $this->getEventUrl(),
            default => null,
        };
    }

    private function getPostUrl(): ?string
    {
        if (isset($this->data['post_id'])) {
            return route('posts.show', $this->data['post_id']);
        }
        return null;
    }

    private function getCommentUrl(): ?string
    {
        if (isset($this->data['comment_id']) && isset($this->data['post_id'])) {
            return route('posts.show', $this->data['post_id']) . '#comment-' . $this->data['comment_id'];
        }
        return $this->getPostUrl();
    }

    private function getProfileUrl(): ?string
    {
        if ($this->fromUser) {
            return route('profile.show', $this->fromUser->username ?? $this->fromUser->name);
        }
        return null;
    }

    private function getMentionUrl(): ?string
    {
        if (isset($this->data['post_id'])) {
            return route('posts.show', $this->data['post_id']);
        }
        if (isset($this->data['comment_id'])) {
            return route('posts.show', $this->data['post_id']) . '#comment-' . $this->data['comment_id'];
        }
        return null;
    }

    private function getMessageUrl(): ?string
    {
        if ($this->fromUser) {
            return route('messages.show', $this->fromUser);
        }
        return null;
    }

    private function getGroupUrl(): ?string
    {
        if (isset($this->data['group_slug'])) {
            return route('groups.show', $this->data['group_slug']);
        }
        return null;
    }

    private function getEventUrl(): ?string
    {
        if (isset($this->data['group_slug']) && isset($this->data['event_id'])) {
            return route('groups.events.show', [$this->data['group_slug'], $this->data['event_id']]);
        }
        return null;
    }

    public function getFormattedMessageAttribute()
    {
        if ($this->message) {
            return $this->message;
        }

        if (!$this->fromUser || !$this->data) {
            return 'New notification';
        }

        $username = $this->fromUser->profile->username ?? $this->fromUser->name;

        return match ($this->type) {
            'like' => $username . ' liked your post',
            'post_like' => $username . ' liked your post',
            'comment_like' => $username . ' liked your comment',
            'comment' => $username . ' commented on your post',
            'reply' => $username . ' replied to your comment',
            'follow' => $username . ' started following you',
            'mention' => $username . ' mentioned you in a post',
            'share' => $username . ' shared your post',
            'post_shared' => $username . ' shared your post',
            'message' => $username . ' sent you a message',
            'group_invite' => $username . ' invited you to join a group',
            'event_reminder' => 'Reminder: ' . ($this->data['event_title'] ?? 'Event') . ' starts soon',
            default => 'New notification from ' . $username,
        };
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function markAsRead()
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    public function markAsUnread(): void
    {
        $this->update(['read_at' => null]);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function isUnread(): bool
    {
        return $this->read_at === null;
    }
}
