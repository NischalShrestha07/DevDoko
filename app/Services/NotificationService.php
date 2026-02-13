<?php
// app/Services/NotificationService.php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send a like notification
     */
    public function likeNotification(User $liker, $likeable, string $type = 'post'): void
    {
        $owner = $type === 'post' ? $likeable->user : $likeable->user;

        // Don't notify if liking own content
        if ($liker->id === $owner->id) {
            return;
        }

        $data = [
            'liker_id' => $liker->id,
            'liker_name' => $liker->name,
            'liker_username' => $liker->profile->username ?? $liker->name,
        ];

        if ($type === 'post') {
            $data['post_id'] = $likeable->id;
            $data['post_title'] = $likeable->title ?? 'post';
            $notificationType = 'post_like';
            $message = $liker->name . ' liked your post';
        } else {
            $data['comment_id'] = $likeable->id;
            $data['post_id'] = $likeable->commentable_id ?? $likeable->post_id;
            $notificationType = 'comment_like';
            $message = $liker->name . ' liked your comment';
        }

        $this->create([
            'user_id' => $owner->id,
            'from_user_id' => $liker->id,
            'type' => $notificationType,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Send a comment notification
     */
    public function commentNotification(User $commenter, Comment $comment, Post $post): void
    {
        // Don't notify if commenting on own post
        if ($commenter->id === $post->user_id) {
            return;
        }

        $this->create([
            'user_id' => $post->user_id,
            'from_user_id' => $commenter->id,
            'type' => 'comment',
            'message' => $commenter->name . ' commented on your post',
            'data' => [
                'commenter_id' => $commenter->id,
                'commenter_name' => $commenter->name,
                'comment_id' => $comment->id,
                'post_id' => $post->id,
                'post_title' => $post->title ?? 'post',
                'comment_excerpt' => substr($comment->content, 0, 100),
            ],
        ]);
    }

    /**
     * Send a reply notification
     */
    public function replyNotification(User $replier, Comment $reply, Comment $parentComment): void
    {
        // Don't notify if replying to own comment
        if ($replier->id === $parentComment->user_id) {
            return;
        }

        $this->create([
            'user_id' => $parentComment->user_id,
            'from_user_id' => $replier->id,
            'type' => 'reply',
            'message' => $replier->name . ' replied to your comment',
            'data' => [
                'replier_id' => $replier->id,
                'replier_name' => $replier->name,
                'reply_id' => $reply->id,
                'parent_comment_id' => $parentComment->id,
                'post_id' => $reply->post_id,
                'reply_excerpt' => substr($reply->content, 0, 100),
            ],
        ]);
    }


    /**
     * Send a share notification
     */
    public function shareNotification(User $sharer, Post $originalPost, Post $sharedPost): void
    {
        // Don't notify if sharing own post
        if ($sharer->id === $originalPost->user_id) {
            return;
        }

        $this->create([
            'user_id' => $originalPost->user_id,
            'from_user_id' => $sharer->id,
            'type' => 'post_shared',
            'message' => $sharer->name . ' shared your post',
            'data' => [
                'sharer_id' => $sharer->id,
                'sharer_name' => $sharer->name,
                'original_post_id' => $originalPost->id,
                'shared_post_id' => $sharedPost->id,
                'post_title' => $originalPost->title ?? 'post',
            ],
        ]);
    }

    /**
     * Send a message notification
     */
    public function messageNotification(User $sender, User $receiver, $messageContent, $messageType = 'text'): void
    {
        // Don't notify if messaging self
        if ($sender->id === $receiver->id) {
            return;
        }

        $this->create([
            'user_id' => $receiver->id,
            'from_user_id' => $sender->id,
            'type' => 'message',
            'message' => $sender->name . ' sent you a message',
            'data' => [
                'sender_id' => $sender->id,
                'sender_name' => $sender->name,
                'content' => substr($messageContent, 0, 200),
                'type' => $messageType,
            ],
        ]);
    }


    /**
     * Create a notification
     */
    private function create(array $data): Notification
    {
        try {
            return Notification::create($data);
        } catch (\Exception $e) {
            Log::error('Failed to create notification', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);
            return null;
        }
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(int $userId): void
    {
        Notification::forUser($userId)
            ->unread()
            ->update(['read_at' => now()]);
    }
}
