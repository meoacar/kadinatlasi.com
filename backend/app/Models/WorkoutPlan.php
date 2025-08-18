<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'goal',
        'level',
        'duration_weeks',
        'days_per_week',
        'weekly_schedule',
        'is_popular'
    ];

    protected $casts = [
        'weekly_schedule' => 'array',
        'is_popular' => 'boolean'
    ];

    public function scopeByGoal($query, $goal)
    {
        return $query->where('goal', $goal);
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function getExercises()
    {
        $exerciseIds = [];
        foreach ($this->weekly_schedule as $day) {
            foreach ($day['exercises'] as $exercise) {
                $exerciseIds[] = $exercise['exercise_id'];
            }
        }
        return Exercise::whereIn('id', array_unique($exerciseIds))->get();
    }
}
