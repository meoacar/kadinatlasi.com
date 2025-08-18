<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DietPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'goal',
        'diet_type',
        'duration_days',
        'daily_calories',
        'daily_meals',
        'is_popular'
    ];

    protected $casts = [
        'daily_meals' => 'array',
        'is_popular' => 'boolean'
    ];

    public function scopeByGoal($query, $goal)
    {
        return $query->where('goal', $goal);
    }

    public function scopeByDietType($query, $dietType)
    {
        return $query->where('diet_type', $dietType);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function getRecipes()
    {
        $recipeIds = [];
        foreach ($this->daily_meals as $day) {
            foreach ($day['meals'] as $meal) {
                $recipeIds[] = $meal['recipe_id'];
            }
        }
        return Recipe::whereIn('id', array_unique($recipeIds))->get();
    }
}
