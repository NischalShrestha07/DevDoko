<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewMentionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $post;
    protected $mentioner;

    public function __construct($post, $mentioner)
    {
        $this->post = $post;
        $this->mentioner = $mentioner;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'mention',
            'post_id' => $this->post->id,
            'mentioner_id' => $this->mentioner->id,
            'mentioner_name' => $this->mentioner->name,
            'message' => "{$this->mentioner->name} mentioned you in a post",
            'link' => route('posts.show', $this->post),
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('You were mentioned in a post on DevDoko')
            ->line("{$this->mentioner->name} mentioned you in their post:")
            ->line($this->post->caption)
            ->action('View Post', route('posts.show', $this->post))
            ->line('Thank you for using DevDoko!');
    }
}
