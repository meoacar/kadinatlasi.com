<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'prep_time',
        'cook_time',
        'servings',
        'calories_per_serving',
        'ingredients',
        'instructions',
        'nutrition_info',
        'diet_type',
        'image_url',
        'difficulty',
        'is_active'
    ];

    protected $casts = [
        'ingredients' => 'array',
        'instructions' => 'array',
        'nutrition_info' => 'array',
        'diet_type' => 'array',
        'is_active' => 'boolean'
    ];

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDietType($query, $dietType)
    {
        return $query->whereJsonContains('diet_type', $dietType);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}