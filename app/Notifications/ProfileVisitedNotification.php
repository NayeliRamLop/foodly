<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProfileVisitedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly User $visitor,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'         => 'profile_visit',
            'actor_id'     => $this->visitor->id,
            'actor_name'   => $this->visitor->name,
            'user_id'      => $this->visitor->id,
            'message'      => "{$this->visitor->name} visitó tu perfil",
            'url'          => route('profile.public', $this->visitor->id),
        ];
    }
}