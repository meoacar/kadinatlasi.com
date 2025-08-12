<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PushNotificationService;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    private $pushService;

    public function __construct(PushNotificationService $pushService)
    {
        $this->pushService = $pushService;
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string'
        ]);

        try {
            $subscription = $this->pushService->subscribe(
                $request->user(),
                $request->all()
            );

            return response()->json([
                'success' => true,
                'message' => 'Push notification aboneliği başarılı',
                'data' => $subscription
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Abonelik sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function unsubscribe(Request $request)
    {
        try {
            $user = $request->user();
            
            // Kullanıcının tüm aboneliklerini deaktif et
            $user->pushSubscriptions()->update(['is_active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Push notification aboneliği iptal edildi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Abonelik iptali sırasında hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendTest(Request $request)
    {
        try {
            $user = $request->user();
            
            $payload = [
                'title' => '🧪 Test Bildirimi',
                'body' => 'Push notification sistemi çalışıyor!',
                'icon' => '/icons/icon-192x192.png',
                'badge' => '/icons/badge-72x72.png',
                'data' => [
                    'url' => '/dashboard',
                    'type' => 'test'
                ]
            ];

            $this->pushService->sendToUser($user, $payload);

            return response()->json([
                'success' => true,
                'message' => 'Test bildirimi gönderildi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test bildirimi gönderilemedi: ' . $e->getMessage()
            ], 500);
        }
    }
}