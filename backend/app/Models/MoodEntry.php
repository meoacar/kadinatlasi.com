<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MoodEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mood',
        'note',
        'date'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getMoodLabelAttribute(): string
    {
        $labels = [
            'very-sad' => 'Çok Üzgün',
            'sad' => 'Üzgün',
            'neutral' => 'Normal',
            'happy' => 'Mutlu',
            'very-happy' => 'Çok Mutlu'
        ];

        return $labels[$this->mood] ?? $this->mood;
    }

    public function getMoodEmojiAttribute(): string
    {
        $emojis = [
            'very-sad' => '😢',
            'sad' => '😔',
            'neutral' => '😐',
            'happy' => '😊',
            'very-happy' => '😄'
        ];

        return $emojis[$this->mood] ?? '😐';
    }
}