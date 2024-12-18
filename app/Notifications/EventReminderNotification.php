<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Event;
class EventReminderNotification extends Notification implements ShouldQueue

{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Event $event
    )
    {
        //


    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Rappel: Tu as un événement à venir!')
                    ->action('Vue événementielle', route('events.show', $this->event->id))
                    ->line("Merci event votre événement denommé {$this->event->name} commencera le {$this->event->start_time} et se finira le {$this->event->end_time}");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
            'event_id'=>$this->event->id,
            'event_name'=>$this->event->name,
            'event_start'=>$this->event->start_time,
            'event_end'=>$this->event->end_time
        ];
    }
}
