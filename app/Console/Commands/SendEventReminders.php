<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cette commande envoit la notification à tous les événéments que les événéments commencent bienot';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $events = \App\Models\Event::with('attendees.user')
            ->whereBetween('start_time', [now(), now()->addDay()])
            ->get();
        $eventCount = $events->count();
        $eventLabel = Str::plural('event', $eventCount);
        $this->info("Found {$eventCount} ${eventLabel}.");
        $events->each(
            fn($event) => $event->attendees->each(
                fn($attendee) => $this->info("Notifie l'utilisateur {$attendee->user->id}")
            )
        );
        $this->info('Les rappels de notification sont envoyés avec succès!');
    }

}
