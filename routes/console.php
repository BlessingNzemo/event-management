<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Define the schedule within a closure
Artisan::command('app:send-event-reminders', function (Schedule $schedule) {
    $schedule->command('app:send-event-reminders')->everyMinute();
})->purpose('Schedule event reminders every fifteen minutes');
