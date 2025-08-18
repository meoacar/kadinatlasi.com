<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PregnancyTracker extends Model
{
    protected $fillable = [
        'user_id',
        'last_menstrual_period',
        'due_date',
        'current_week',
        'current_day',
        'weight_gain',
        'symptoms',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'last_menstrual_period' => 'date',
        'due_date' => 'date',
        'weight_gain' => 'decimal:2',
        'symptoms' => 'array',
        'notes' => 'array',
        'is_active' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCurrentWeekInfo()
    {
        return PregnancyWeek::where('week_number', $this->current_week)->first();
    }
}
