<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserReputation;
use App\Models\Badge;

class ReputationService
{
    public static function addPoints($userId, $actionType, $source = null)
    {
        $points = self::getPointsForAction($actionType);
        
        if ($points > 0) {
            UserReputation::addPoints(
                $userId,
                $actionType,
                $points,
                $source,
                self::getDescriptionForAction($actionType)
            );
            
            self::checkBadges($userId);
        }
    }

    private static function getPointsForAction($actionType)
    {
        return match($actionType) {
            'forum_post_created' => 5,
            'forum_post_liked' => 2,
            'blog_post_created' => 10,
            'blog_post_liked' => 3,
            'expert_answer_given' => 15,
            'expert_answer_accepted' => 25,
            'daily_checkin' => 1,
            'badge_earned' => 50,
            default => 0
        };
    }

    private static function getDescriptionForAction($actionType)
    {
        return match($actionType) {
            'forum_post_created' => 'Forum yazısı oluşturuldu',
            'forum_post_liked' => 'Forum yazısı beğenildi',
            'blog_post_created' => 'Blog yazısı oluşturuldu',
            'blog_post_liked' => 'Blog yazısı beğenildi',
            'expert_answer_given' => 'Uzman cevabı verildi',
            'expert_answer_accepted' => 'Uzman cevabı kabul edildi',
            'daily_checkin' => 'Günlük giriş yapıldı',
            'badge_earned' => 'Rozet kazanıldı',
            default => 'Bilinmeyen eylem'
        };
    }

    private static function checkBadges($userId)
    {
        $user = User::find($userId);
        $badges = Badge::where('is_active', true)->get();
        
        foreach ($badges as $badge) {
            if (!$user->badges()->where('badge_id', $badge->id)->exists()) {
                if (self::checkBadgeCriteria($user, $badge)) {
                    $user->badges()->attach($badge->id);
                }
            }
        }
    }

    private static function checkBadgeCriteria($user, $badge)
    {
        $criteria = $badge->criteria;
        
        return match($criteria['action']) {
            'register' => true,
            'reputation_score' => $user->reputation_score >= $criteria['count'],
            'likes_received' => $user->reputations()
                ->where('action_type', 'LIKE', '%_liked')
                ->count() >= $criteria['count'],
            default => false
        };
    }
}