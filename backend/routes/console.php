<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\SendMenstrualReminders;
use App\Jobs\SendDailyReminderJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule jobs
Schedule::job(new SendMenstrualReminders())->daily();
Schedule::job(new SendDailyReminderJob())->dailyAt('09:00');
Schedule::job(new SendDailyReminderJob())->dailyAt('20:00');
