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
        'brand',
        'dish_type',
        'daily_category',
        'special_occasion',
        'baking_category',
        'seasonality',
        'preparation_method',
        'image',
        'video',
        'video_link',
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

    public function getVideoEmbedUrlAttribute(): ?string
    {
        $parsed = $this->parseVideoLink();
        if (!$parsed) {
            return null;
        }

        $url = $parsed['url'];
        $parts = $parsed['parts'];
        $host = strtolower($parts['host']);
        parse_str($parts['query'] ?? '', $query);
        $path = trim($parts['path'] ?? '', '/');

        if (str_contains($host, 'youtube.com') || str_contains($host, 'youtu.be')) {
            $videoId = null;

            if (str_contains($host, 'youtu.be')) {
                $videoId = strtok($path, '/');
            } elseif (!empty($query['v'])) {
                $videoId = $query['v'];
            } elseif (str_starts_with($path, 'embed/')) {
                $videoId = substr($path, 6);
            } elseif (str_starts_with($path, 'shorts/')) {
                $videoId = substr($path, 7);
            }

            return $videoId ? "https://www.youtube.com/embed/{$videoId}" : null;
        }

        if (str_contains($host, 'vimeo.com')) {
            $segments = explode('/', $path);
            $videoId = end($segments);
            return $videoId ? "https://player.vimeo.com/video/{$videoId}" : null;
        }

        if (str_contains($host, 'tiktok.com')) {
            return "https://www.tiktok.com/embed/v2/{$path}";
        }

        return null;
    }

    public function getVideoLinkTypeAttribute(): ?string
    {
        if ($this->video_embed_url) {
            return 'embed';
        }

        return $this->video_direct_url ? 'direct' : null;
    }

    public function getVideoDirectUrlAttribute(): ?string
    {
        $parsed = $this->parseVideoLink();
        if (!$parsed) {
            return null;
        }

        $url = $parsed['url'];
        $parts = $parsed['parts'];
        $path = strtolower($parts['path'] ?? '');
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        $allowedExtensions = ['mp4', 'webm', 'ogg', 'm4v', 'mov'];
        if (in_array($extension, $allowedExtensions, true)) {
            return $url;
        }

        return null;
    }

    private function parseVideoLink(): ?array
    {
        if (!$this->video_link) {
            return null;
        }

        $url = trim($this->video_link);
        $parts = parse_url($url);
        if (!$parts || empty($parts['host'])) {
            return null;
        }

        return [
            'url' => $url,
            'parts' => $parts,
        ];
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
