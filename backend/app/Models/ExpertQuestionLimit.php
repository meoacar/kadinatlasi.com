<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpertQuestionLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'questions_asked',
        'month',
        'year'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getQuestionLimit($membershipType): int
    {
        return match($membershipType) {
            'basic' => 2,
            'premium' => 10,
            'vip' => 20,
            default => 0 // free users can't ask questions
        };
    }

    public static function getUserQuestionCount($userId): int
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $limit = self::firstOrCreate([
            'user_id' => $userId,
            'month' => $currentMonth,
            'year' => $currentYear
        ], [
            'questions_asked' => 0
        ]);

        return $limit->questions_asked;
    }

    public static function incrementQuestionCount($userId): void
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $limit = self::firstOrCreate([
            'user_id' => $userId,
            'month' => $currentMonth,
            'year' => $currentYear
        ], [
            'questions_asked' => 0
        ]);

        $limit->increment('questions_asked');
    }

    public static function canAskQuestion($user): bool
    {
        if (!$user->premium_subscription) {
            return false; // Free users can't ask questions
        }

        $membershipType = $user->premium_subscription->plan->slug ?? 'free';
        $limit = self::getQuestionLimit($membershipType);
        $currentCount = self::getUserQuestionCount($user->id);

        return $currentCount < $limit;
    }

    public static function getRemainingQuestions($user): int
    {
        if (!$user->premium_subscription) {
            return 0;
        }

        $membershipType = $user->premium_subscription->plan->slug ?? 'free';
        $limit = self::getQuestionLimit($membershipType);
        $currentCount = self::getUserQuestionCount($user->id);

        return max(0, $limit - $currentCount);
    }
}