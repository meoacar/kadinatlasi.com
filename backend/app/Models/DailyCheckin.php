<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyCheckin extends Model
{
    protected $fillable = [
        'user_id',
        'checkin_date',
        'activities',
        'mood_score',
        'notes',
        'points_earned'
    ];

    protected $casts = [
        'checkin_date' => 'date',
        'activities' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('checkin_date', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('checkin_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('checkin_date', now()->month)
                    ->whereYear('checkin_date', now()->year);
    }
}