<?php


namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewFollowerNotification extends Notification
{
    protected $follower;

    public function __construct($follower)
    {
        $this->follower = $follower;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->name,
            'message' => "{$this->follower->name} started following you",
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Follower on DevHub')
            ->line("{$this->follower->name} started following you on DevHub")
            ->action('View Profile', url("/@{$this->follower->profile->username}"))
            ->line('Thank you for using DevHub!');
    }
}
