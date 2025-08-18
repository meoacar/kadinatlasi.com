<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ForumLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'topics_created',
        'posts_created',
        'period_start',
        'period_end'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getCurrentPeriodLimits($userId)
    {
        $now = Carbon::now();
        $periodStart = $now->startOfMonth()->toDateString();
        $periodEnd = $now->endOfMonth()->toDateString();

        return self::firstOrCreate(
            [
                'user_id' => $userId,
                'period_start' => $periodStart
            ],
            [
                'period_end' => $periodEnd,
                'topics_created' => 0,
                'posts_created' => 0
            ]
        );
    }

    public static function getUserLimits($userId)
    {
        $user = User::find($userId);
        $isAdmin = $user->email === 'admin@kadinatlasi.com' || $user->hasRole('admin');
        
        if ($isAdmin) {
            return [
                'topic_limit' => -1, // Unlimited
                'post_limit' => -1,  // Unlimited
                'topics_used' => 0,
                'posts_used' => 0
            ];
        }

        // Get premium subscription
        $premiumSubscription = PremiumSubscription::where('user_id', $userId)
            ->where('status', 'active')
            ->first();

        $limits = [
            'free' => ['topics' => 1, 'posts' => 1],
            'basic' => ['topics' => 5, 'posts' => 10],
            'premium' => ['topics' => 20, 'posts' => 100],
            'vip' => ['topics' => -1, 'posts' => -1] // Unlimited
        ];

        $userType = 'free';
        if ($premiumSubscription) {
            $userType = $premiumSubscription->plan_type;
        }

        $currentLimits = self::getCurrentPeriodLimits($userId);

        return [
            'topic_limit' => $limits[$userType]['topics'],
            'post_limit' => $limits[$userType]['posts'],
            'topics_used' => $currentLimits->topics_created,
            'posts_used' => $currentLimits->posts_created
        ];
    }

    public static function canCreateTopic($userId)
    {
        $limits = self::getUserLimits($userId);
        
        if ($limits['topic_limit'] === -1) {
            return true; // Unlimited
        }
        
        return $limits['topics_used'] < $limits['topic_limit'];
    }

    public static function canCreatePost($userId)
    {
        $limits = self::getUserLimits($userId);
        
        if ($limits['post_limit'] === -1) {
            return true; // Unlimited
        }
        
        return $limits['posts_used'] < $limits['post_limit'];
    }

    public static function incrementTopicCount($userId)
    {
        $currentLimits = self::getCurrentPeriodLimits($userId);
        $currentLimits->increment('topics_created');
    }

    public static function incrementPostCount($userId)
    {
        $currentLimits = self::getCurrentPeriodLimits($userId);
        $currentLimits->increment('posts_created');
    }
}