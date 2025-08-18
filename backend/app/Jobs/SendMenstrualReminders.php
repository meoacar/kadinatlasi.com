<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserProfile;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendMenstrualReminders implements ShouldQueue
{
    use Queueable;

    protected $notificationService;

    public function __construct()
    {
        $this->notificationService = app(NotificationService::class);
    }

    public function handle(): void
    {
        // Regl döngüsü bilgisi olan kullanıcıları bul
        $users = User::whereHas('profile', function ($query) {
            $query->whereNotNull('last_period_date')
                  ->whereNotNull('cycle_length');
        })->with('profile')->get();

        foreach ($users as $user) {
            $profile = $user->profile;
            
            if (!$profile->last_period_date || !$profile->cycle_length) {
                continue;
            }

            $lastPeriod = Carbon::parse($profile->last_period_date);
            $cycleLength = $profile->cycle_length ?? 28;
            $nextPeriod = $lastPeriod->addDays($cycleLength);
            $today = Carbon::today();

            // 3 gün önceden hatırlatma gönder
            $reminderDate = $nextPeriod->subDays(3);
            
            if ($today->isSameDay($reminderDate)) {
                $this->notificationService->notifyMenstrualReminder($user->id, [
                    'message' => 'Regl döneminiz 3 gün sonra başlayabilir. Hazırlıklarınızı yapabilirsiniz.',
                    'next_period_date' => $nextPeriod->format('Y-m-d'),
                    'days_remaining' => 3
                ]);
            }

            // Regl günü hatırlatması
            if ($today->isSameDay($nextPeriod)) {
                $this->notificationService->notifyMenstrualReminder($user->id, [
                    'message' => 'Bugün regl döneminizin başlama tarihi. Sağlığınıza dikkat edin.',
                    'next_period_date' => $nextPeriod->format('Y-m-d'),
                    'days_remaining' => 0
                ]);
            }

            // Geç kalma hatırlatması (3 gün sonra)
            $lateDate = $nextPeriod->addDays(3);
            if ($today->isSameDay($lateDate)) {
                $this->notificationService->notifyMenstrualReminder($user->id, [
                    'message' => 'Regl döneminiz gecikmiş olabilir. Sağlık durumunuzu kontrol ettirmeyi düşünün.',
                    'expected_date' => $nextPeriod->format('Y-m-d'),
                    'days_late' => 3
                ]);
            }
        }
    }
}
