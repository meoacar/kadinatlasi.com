<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\User;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationService
{
    private $webPush;

    public function __construct()
    {
        $this->webPush = new WebPush([
            'VAPID' => [
                'subject' => config('app.url'),
                'publicKey' => config('services.vapid.public_key'),
                'privateKey' => config('services.vapid.private_key'),
            ]
        ]);
    }

    public function subscribe(User $user, array $subscriptionData)
    {
        return PushSubscription::updateOrCreate([
            'user_id' => $user->id,
            'endpoint' => $subscriptionData['endpoint']
        ], [
            'p256dh_key' => $subscriptionData['keys']['p256dh'],
            'auth_token' => $subscriptionData['keys']['auth'],
            'user_agent' => request()->userAgent(),
            'is_active' => true
        ]);
    }

    public function sendToUser(User $user, array $payload)
    {
        $subscriptions = PushSubscription::where('user_id', $user->id)
            ->active()
            ->get();

        foreach ($subscriptions as $subscription) {
            $this->sendNotification($subscription, $payload);
        }
    }

    public function sendToAll(array $payload)
    {
        $subscriptions = PushSubscription::active()->get();
        
        foreach ($subscriptions as $subscription) {
            $this->sendNotification($subscription, $payload);
        }
    }

    private function sendNotification(PushSubscription $subscription, array $payload)
    {
        try {
            $webPushSubscription = Subscription::create([
                'endpoint' => $subscription->endpoint,
                'keys' => [
                    'p256dh' => $subscription->p256dh_key,
                    'auth' => $subscription->auth_token
                ]
            ]);

            $result = $this->webPush->sendOneNotification(
                $webPushSubscription,
                json_encode($payload)
            );

            if (!$result->isSuccess()) {
                // Subscription geçersizse deaktif et
                if ($result->getStatusCode() === 410) {
                    $subscription->update(['is_active' => false]);
                }
            }

            return $result->isSuccess();
        } catch (\Exception $e) {
            \Log::error('Push notification error: ' . $e->getMessage());
            return false;
        }
    }

    public function sendDailyReminder()
    {
        $payload = [
            'title' => '🌟 Günlük Check-in Zamanı!',
            'body' => 'Bugünkü aktivitelerinizi kaydetmeyi unutmayın!',
            'icon' => '/icons/icon-192x192.png',
            'badge' => '/icons/badge-72x72.png',
            'data' => [
                'url' => '/dashboard',
                'type' => 'daily_reminder'
            ]
        ];

        $this->sendToAll($payload);
    }

    public function sendEventReminder(User $user, $event)
    {
        $payload = [
            'title' => '📅 Etkinlik Hatırlatması',
            'body' => $event->title . ' etkinliği yakında başlıyor!',
            'icon' => '/icons/icon-192x192.png',
            'data' => [
                'url' => '/etkinlikler',
                'type' => 'event_reminder',
                'event_id' => $event->id
            ]
        ];

        $this->sendToUser($user, $payload);
    }

    public function sendAchievementNotification(User $user, $achievement)
    {
        $payload = [
            'title' => '🏆 Yeni Rozet Kazandınız!',
            'body' => $achievement->name . ' rozetini kazandınız!',
            'icon' => '/icons/icon-192x192.png',
            'data' => [
                'url' => '/dashboard',
                'type' => 'achievement',
                'achievement_id' => $achievement->id
            ]
        ];

        $this->sendToUser($user, $payload);
    }
}