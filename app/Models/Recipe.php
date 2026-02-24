<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends Model
{
    use HasFactory;

    protected $table = 'recipes';

    protected $fillable = [
        'recipe_title',
        'recipe_description',
        'ingredients',
        'instructions',
        'preparation_time',
        'cooking_timer',
        'difficulty',
        'user_id',
        'category_id',
        'subcategory_id',
        'image',
        'video',
        'status'
        
    ];

    /**
     * Relación con el usuario creador de la receta
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
        
    }

    /**
     * Relación con la categoría a la que pertenece la receta
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    /**
     * Relación con subcategoría si existe
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    /**
     * Accesor para obtener la URL de la imagen
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }

    /**
     * Accesor para obtener la URL del video
     */
    public function getVideoUrlAttribute()
    {
        return $this->video ? asset('storage/'.$this->video) : null;
    }
    // Agrega este método al modelo Recipe
public function favoritedBy()
{
    return $this->belongsToMany(User::class, 'favorites', 'recipe_id', 'user_id')
                ->withTimestamps();
}
public function ratings(): HasMany
{
    return $this->hasMany(RecipeRating::class);
}
public function comments(): HasMany
{
    return $this->hasMany(RecipeComment::class);
}
 public function scopeActive($query)
{
    return $query->where('status', 1);
}
}
