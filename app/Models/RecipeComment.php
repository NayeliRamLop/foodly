<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RecipeComment extends Model
{
    protected $fillable = [
        'recipe_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function reactions(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'recipe_comment_reactions', 'recipe_comment_id', 'user_id')
            ->withTimestamps();
    }
}
