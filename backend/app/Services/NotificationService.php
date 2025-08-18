<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Jobs\SendPushNotificationJob;

class NotificationService
{
    public function createNotification(
        int $userId,
        string $type,
        string $title,
        string $message,
        array $data = [],
        bool $sendPush = true
    ): Notification {
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ]);

        // Push notification gönder
        if ($sendPush) {
            $user = User::find($userId);
            if ($user) {
                $payload = [
                    'title' => $title,
                    'body' => $message,
                    'icon' => '/icons/icon-192x192.png',
                    'badge' => '/icons/badge-72x72.png',
                    'data' => array_merge($data, [
                        'notification_id' => $notification->id,
                        'type' => $type,
                        'url' => $this->getNotificationUrl($type, $data)
                    ])
                ];
                
                SendPushNotificationJob::dispatch($user, $payload);
            }
        }

        return $notification;
    }

    public function notifyForumReply(int $userId, array $data): Notification
    {
        return $this->createNotification(
            $userId,
            'forum_reply',
            'Konunuza Yeni Yanıt',
            "'{$data['topic_title']}' konunuza yeni bir yanıt geldi.",
            $data
        );
    }

    public function notifyBlogComment(int $userId, array $data): Notification
    {
        return $this->createNotification(
            $userId,
            'blog_comment',
            'Blog Yazınıza Yorum',
            "'{$data['blog_title']}' yazınıza yeni bir yorum yapıldı.",
            $data
        );
    }

    public function notifyMenstrualReminder(int $userId, array $data): Notification
    {
        return $this->createNotification(
            $userId,
            'menstrual_reminder',
            'Regl Döngüsü Hatırlatması',
            $data['message'] ?? 'Regl döneminiz yaklaşıyor.',
            $data
        );
    }

    public function notifyWaterReminder(int $userId): Notification
    {
        return $this->createNotification(
            $userId,
            'water_reminder',
            'Su İçme Hatırlatması',
            'Günlük su hedefinizi unutmayın! 💧'
        );
    }

    public function notifyExerciseReminder(int $userId, array $data): Notification
    {
        return $this->createNotification(
            $userId,
            'exercise_reminder',
            'Egzersiz Hatırlatması',
            $data['message'] ?? 'Bugünkü egzersiz programınızı yapmayı unutmayın! 💪'
        );
    }

    public function getUserNotifications(int $userId, int $limit = 20)
    {
        return Notification::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getUnreadCount(int $userId): int
    {
        return Notification::forUser($userId)->unread()->count();
    }

    public function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = Notification::forUser($userId)->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            return true;
        }
        
        return false;
    }

    public function markAllAsRead(int $userId): int
    {
        return Notification::forUser($userId)
            ->unread()
            ->update(['read_at' => now()]);
    }

    private function getNotificationUrl(string $type, array $data): string
    {
        switch ($type) {
            case 'forum_reply':
                return '/forum/topic/' . ($data['topic_id'] ?? '');
            case 'blog_comment':
                return '/blog/' . ($data['blog_id'] ?? '');
            case 'menstrual_reminder':
            case 'water_reminder':
            case 'exercise_reminder':
                return '/dashboard';
            default:
                return '/notifications';
        }
    }
}