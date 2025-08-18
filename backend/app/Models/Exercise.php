<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'difficulty',
        'duration_minutes',
        'calories_burned',
        'equipment_needed',
        'instructions',
        'video_url',
        'image_url',
        'muscle_groups',
        'is_active'
    ];

    protected $casts = [
        'muscle_groups' => 'array',
        'is_active' => 'boolean'
    ];

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}