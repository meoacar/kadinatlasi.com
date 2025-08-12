<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $notifications = $this->notificationService->getUserNotifications(
            $request->user()->id,
            $request->get('limit', 20)
        );

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    public function unreadCount(Request $request)
    {
        $count = $this->notificationService->getUnreadCount($request->user()->id);

        return response()->json([
            'success' => true,
            'data' => ['count' => $count]
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $success = $this->notificationService->markAsRead($id, $request->user()->id);

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Bildirim okundu olarak işaretlendi' : 'Bildirim bulunamadı'
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $count = $this->notificationService->markAllAsRead($request->user()->id);

        return response()->json([
            'success' => true,
            'data' => ['marked_count' => $count],
            'message' => "{$count} bildirim okundu olarak işaretlendi"
        ]);
    }
}
