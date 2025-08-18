<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'interests',
        'health_info',
        'last_period_date',
        'cycle_length',
        'goals',
        'achievements',
        'points',
        'level',
    ];

    protected $casts = [
        'interests' => 'array',
        'health_info' => 'array',
        'goals' => 'array',
        'achievements' => 'array',
        'last_period_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Calculate next period date
    public function getNextPeriodDateAttribute()
    {
        if (!$this->last_period_date) {
            return null;
        }
        
        return $this->last_period_date->addDays($this->cycle_length);
    }

    // Calculate ovulation date
    public function getOvulationDateAttribute()
    {
        if (!$this->last_period_date) {
            return null;
        }
        
        return $this->last_period_date->addDays($this->cycle_length - 14);
    }
}