<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('app:send-event-reminders', function () {

    $events = \App\Models\Event::with('attendees.user')
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get();
        $eventCount = $events->count();
        $eventLabel = \Illuminate\Support\Str::plural('event', $eventCount);
        $this->info("Found {$eventCount} {$eventLabel}.");
        $events->each(
            fn($event) => $event->attendees->each(
                fn($attendee) => $this->info("Notifie l'utilisateur {$attendee->user->id}")
            )
        );
        $this->info('Les rappels de notification sont envoyés avec succès!');

})->purpose('Affiche les notifications des mails')->everyMinute();
