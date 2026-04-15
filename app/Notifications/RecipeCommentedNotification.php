<?php

namespace App\Notifications;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RecipeCommentedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly User $actor,
        public readonly Recipe $recipe,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'        => 'recipe_comment',
            'actor_id'    => $this->actor->id,
            'actor_name'  => $this->actor->name,
            'recipe_id'   => $this->recipe->id,
            'recipe_title'=> $this->recipe->recipe_title,
            'message'     => "{$this->actor->name} comentó tu receta \"{$this->recipe->recipe_title}\"",
            'url'         => route('recipes.index', ['open_recipe' => $this->recipe->id]),
        ];
    }
}