<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Notifications\CustomResetPasswordNotification; // Importa tu notificación personalizada
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'last_name',
        'gender',
        'email',
        'phone',
        'country',
        'registration_date',
        'password',
        'avatar',
        'status',
         'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Accesor para obtener la URL completa del avatar
    public function getAvatarUrlAttribute()
    {
        return $this->avatar 
            ? asset('storage/'.$this->avatar)
            : asset('images/default-avatar.png');
    }

    /**
     * Sobrescribe el método para usar tu notificación personalizada
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
     public function categoriasPreferidas(): BelongsToMany
    {
       return $this->belongsToMany(Category::class, 'user_category_preferencias', 'user_id', 'categoria_id');
}
public function recipes()
{
    return $this->hasMany(Recipe::class);
}
public function recipeRatings(): HasMany
{
    return $this->hasMany(RecipeRating::class);
}
public function favorites()
{
    return $this->belongsToMany(Recipe::class, 'favorites', 'user_id', 'recipe_id')
                ->withTimestamps();
}
public function followers(): BelongsToMany
{
    return $this->belongsToMany(User::class, 'user_followers', 'user_id', 'follower_id')
                ->withTimestamps();
}
public function following(): BelongsToMany
{
    return $this->belongsToMany(User::class, 'user_followers', 'follower_id', 'user_id')
                ->withTimestamps();
}

public function isAdmin()
{
    return  strtolower(trim($this->role)) === 'admin';
}
public function isUser()
    {
        return strtolower(trim($this->role)) === 'user';
    }
    public function isActive()
{
    return $this->status == 1;
}
}
