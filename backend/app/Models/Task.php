<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'type',
        'category',
        'points',
        'xp_reward',
        'requirements',
        'action_type',
        'target_count',
        'is_active',
        'start_date',
        'end_date',
        'sort_order'
    ];

    protected $casts = [
        'requirements' => 'array',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function userTasks()
    {
        return $this->hasMany(UserTask::class);
    }

    public function getUserProgress($userId, $date = null)
    {
        $date = $date ?? now()->toDateString();
        return $this->userTasks()
            ->where('user_id', $userId)
            ->where('task_date', $date)
            ->first();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}