<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\PushNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $payload;

    public function __construct(User $user, array $payload)
    {
        $this->user = $user;
        $this->payload = $payload;
    }

    public function handle(PushNotificationService $pushService)
    {
        $pushService->sendToUser($this->user, $this->payload);
    }
}