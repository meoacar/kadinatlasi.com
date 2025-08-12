<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'goal',
        'difficulty',
        'duration_weeks',
        'days_per_week',
        'exercises',
        'schedule',
        'image_url',
        'trainer_name',
        'is_premium',
        'is_active'
    ];

    protected $casts = [
        'exercises' => 'array',
        'schedule' => 'array',
        'is_premium' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function scopeByGoal($query, $goal)
    {
        return $query->where('goal', $goal);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFree($query)
    {
        return $query->where('is_premium', false);
    }
}