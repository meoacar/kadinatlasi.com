<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'level',
        'current_xp',
        'total_xp',
        'points',
        'achievements_count',
        'tasks_completed',
        'daily_streak',
        'max_streak',
        'last_activity_date',
        'statistics'
    ];

    protected $casts = [
        'last_activity_date' => 'date',
        'statistics' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getXpForNextLevelAttribute()
    {
        return $this->calculateXpForLevel($this->level + 1);
    }

    public function getXpProgressAttribute()
    {
        $currentLevelXp = $this->calculateXpForLevel($this->level);
        $nextLevelXp = $this->calculateXpForLevel($this->level + 1);
        $progressXp = $this->total_xp - $currentLevelXp;
        $requiredXp = $nextLevelXp - $currentLevelXp;
        
        return $requiredXp > 0 ? ($progressXp / $requiredXp) * 100 : 0;
    }

    public function calculateXpForLevel($level)
    {
        // XP formula: level^2 * 100
        return pow($level - 1, 2) * 100;
    }

    public function addXp($xp)
    {
        $this->current_xp += $xp;
        $this->total_xp += $xp;
        
        // Check for level up
        while ($this->current_xp >= $this->calculateXpForLevel($this->level + 1) - $this->calculateXpForLevel($this->level)) {
            $this->current_xp -= ($this->calculateXpForLevel($this->level + 1) - $this->calculateXpForLevel($this->level));
            $this->level++;
        }
        
        $this->save();
        return $this;
    }

    public function addPoints($points)
    {
        $this->points += $points;
        $this->save();
        return $this;
    }
}