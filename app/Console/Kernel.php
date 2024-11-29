<?php

namespace App\Console;

use App\Console\Commands\SendEventReminders; // Import your command
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Schedule the app:send-event-reminders command to run daily
        $schedule->command(SendEventReminders::class)->everyMinute(); // Change to your desired frequency
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        // Other command registrations...
    }
}
