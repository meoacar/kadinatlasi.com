<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achievement extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'icon',
        'badge_color',
        'type',
        'difficulty',
        'points',
        'requirements',
        'target_value',
        'target_metric',
        'is_active',
        'is_hidden',
        'sort_order'
    ];

    protected $casts = [
        'requirements' => 'array',
        'is_active' => 'boolean',
        'is_hidden' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AchievementCategory::class, 'category_id');
    }

    public function userAchievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }
}