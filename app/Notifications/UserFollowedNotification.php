<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class UserFollowedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly User $actor,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'       => 'follow',
            'actor_id'   => $this->actor->id,
            'actor_name' => $this->actor->name,
            'user_id'    => $this->actor->id,
            'message'    => "{$this->actor->name} comenzó a seguirte",
            'url'        => route('profile.public', $this->actor->id),
        ];
    }
}