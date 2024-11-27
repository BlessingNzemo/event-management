<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Event;
use App\Models\Attendee;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //

        Gate::define('update-event', function ($user, Event $event) {
            return $user->id === $event->user_id;
        });

        Gate::define('delete-attendee', function ($user, Event $event, Attendee $attendee) {
            return $user->id === $attendee->user_id || $attendee->user_id === $event->user_id;
        });
    }
}
