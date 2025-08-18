<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MoodTracker extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'mood',
        'energy_level',
        'stress_level',
        'notes',
        'activities'
    ];

    protected $casts = [
        'date' => 'date',
        'activities' => 'array',
        'energy_level' => 'integer',
        'stress_level' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getMoodLabelAttribute()
    {
        $labels = [
            'very_sad' => 'Çok Üzgün',
            'sad' => 'Üzgün',
            'neutral' => 'Normal',
            'happy' => 'Mutlu',
            'very_happy' => 'Çok Mutlu'
        ];
        return $labels[$this->mood] ?? $this->mood;
    }
}
