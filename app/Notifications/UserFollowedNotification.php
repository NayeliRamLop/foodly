<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserFollowedNotification extends Notification
{
    use Queueable;

    public function __construct(private User $follower)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'follow',
            'message' => $this->follower->name . ' empezó a seguirte.',
            'user_id' => $this->follower->id,
            'url' => route('profile.public', $this->follower),
        ];
    }
}
