<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'birth_date',
        'zodiac_sign',
        'avatar',
        'is_active',
        'membership_type',
        'membership_expires_at',
        'points',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'birth_date' => 'date',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'membership_expires_at' => 'datetime',
        ];
    }

    // Relations
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function forumTopics()
    {
        return $this->hasMany(ForumTopic::class);
    }

    public function forumReplies()
    {
        return $this->hasMany(ForumReply::class);
    }

    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(Notification::class)->unread();
    }

    public function expertApplication()
    {
        return $this->hasOne(ExpertApplication::class)->where('status', 'approved');
    }

    public function getExpertRankAttribute()
    {
        $expertApp = $this->expertApplication;
        if ($expertApp) {
            return match($expertApp->profession) {
                'doktor' => '👩⚕️ Uzman Doktor',
                'psikolog' => '🧠 Uzman Psikolog',
                'diyetisyen' => '🥗 Uzman Diyetisyen',
                'ebe' => '🤱 Uzman Ebe',
                'fizyoterapist' => '💪 Uzman Fizyoterapist',
                'avukat' => '⚖️ Uzman Avukat',
                'egitmen' => '📚 Uzman Eğitmen',
                default => '✨ Uzman'
            };
        }
        return null;
    }

    public function getIsExpertAttribute()
    {
        return $this->expertApplication !== null;
    }

    public function expertQuestions()
    {
        return $this->hasMany(ExpertQuestion::class);
    }

    public function answeredQuestions()
    {
        return $this->hasMany(ExpertQuestion::class, 'expert_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
                    ->where('status', 'active')
                    ->where('expires_at', '>', now())
                    ->latest();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function hasPremiumMembership()
    {
        return in_array($this->membership_type, ['basic', 'premium', 'vip']) && 
               ($this->membership_expires_at === null || $this->membership_expires_at > now());
    }

    public function getMembershipStatusAttribute()
    {
        if (!$this->hasPremiumMembership()) {
            return 'normal';
        }
        
        if ($this->membership_expires_at && $this->membership_expires_at <= now()) {
            return 'expired';
        }
        
        return $this->membership_type;
    }

    public function getMembershipLimitsAttribute()
    {
        return match($this->membership_type) {
            'basic' => [
                'expert_questions' => 5,
                'forum_posts' => 20,
                'second_hand_listings' => 5,
            ],
            'premium' => [
                'expert_questions' => 15,
                'forum_posts' => 50,
                'second_hand_listings' => 10,
            ],
            'vip' => [
                'expert_questions' => -1, // unlimited
                'forum_posts' => -1,
                'second_hand_listings' => 20,
            ],
            default => [
                'expert_questions' => 2,
                'forum_posts' => 5,
                'second_hand_listings' => 1,
            ]
        };
    }

    public function hasPremiumAccess($feature = null)
    {
        if (!$this->hasPremiumMembership()) {
            return false;
        }

        if (!$feature) {
            return true;
        }

        $premiumFeatures = [
            'expert_questions' => ['basic', 'premium', 'vip'],
            'premium_content' => ['premium', 'vip'],
            'advanced_calculators' => ['premium', 'vip'],
            'exclusive_forum' => ['vip'],
            'personal_consultation' => ['vip'],
            'ad_free' => ['premium', 'vip'],
            'priority_support' => ['premium', 'vip']
        ];

        return isset($premiumFeatures[$feature]) && 
               in_array($this->membership_type, $premiumFeatures[$feature]);
    }

    public function canAccessPremiumContent($contentType = 'general')
    {
        return $this->hasPremiumAccess('premium_content');
    }

    public function dailyCheckins()
    {
        return $this->hasMany(DailyCheckin::class);
    }

    public function userLevel()
    {
        return $this->hasOne(UserLevel::class);
    }

    public function userTasks()
    {
        return $this->hasMany(UserTask::class);
    }

    public function userAchievements()
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Accessors
    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function getZodiacSignAttribute($value)
    {
        if (!$value && $this->birth_date) {
            return $this->calculateZodiacSign($this->birth_date);
        }
        return $value;
    }

    private function calculateZodiacSign($date)
    {
        $month = $date->month;
        $day = $date->day;

        $signs = [
            'Oğlak' => [['month' => 12, 'day' => 22], ['month' => 1, 'day' => 19]],
            'Kova' => [['month' => 1, 'day' => 20], ['month' => 2, 'day' => 18]],
            'Balık' => [['month' => 2, 'day' => 19], ['month' => 3, 'day' => 20]],
            'Koç' => [['month' => 3, 'day' => 21], ['month' => 4, 'day' => 19]],
            'Boğa' => [['month' => 4, 'day' => 20], ['month' => 5, 'day' => 20]],
            'İkizler' => [['month' => 5, 'day' => 21], ['month' => 6, 'day' => 20]],
            'Yengeç' => [['month' => 6, 'day' => 21], ['month' => 7, 'day' => 22]],
            'Aslan' => [['month' => 7, 'day' => 23], ['month' => 8, 'day' => 22]],
            'Başak' => [['month' => 8, 'day' => 23], ['month' => 9, 'day' => 22]],
            'Terazi' => [['month' => 9, 'day' => 23], ['month' => 10, 'day' => 22]],
            'Akrep' => [['month' => 10, 'day' => 23], ['month' => 11, 'day' => 21]],
            'Yay' => [['month' => 11, 'day' => 22], ['month' => 12, 'day' => 21]],
        ];

        foreach ($signs as $sign => $ranges) {
            foreach ($ranges as $range) {
                if ($month == $range['month'] && $day >= $range['day']) {
                    return $sign;
                }
                if ($month == ($range['month'] % 12) + 1 && $day <= $range['day']) {
                    return $sign;
                }
            }
        }

        return null;
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function reputations()
    {
        return $this->hasMany(UserReputation::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withTimestamps();
    }

    public function getReputationScoreAttribute()
    {
        return $this->reputations()->sum('points');
    }

    public function getReputationLevelAttribute()
    {
        $score = $this->reputation_score;
        if ($score >= 1000) return 'Uzman';
        if ($score >= 500) return 'İleri';
        if ($score >= 100) return 'Orta';
        return 'Başlangıç';
    }
}
