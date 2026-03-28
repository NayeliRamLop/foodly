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
        private User $actor,
        private Recipe $recipe
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'recipe_comment',
            'message' => $this->actor->name . ' comentó tu receta "' . $this->recipe->recipe_title . '".',
            'user_id' => $this->actor->id,
            'recipe_id' => $this->recipe->id,
            'url' => route('recipes.index', ['open_recipe' => $this->recipe->id]),
        ];
    }
}
