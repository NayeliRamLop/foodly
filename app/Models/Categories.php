<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $table = 'categories';
    
    protected $fillable = [
        'name', 
        'category_name',
        'category_description'
    ];

    public function subcategories()
    {
         return $this->hasMany(Subcategory::class, 'category_id');
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}