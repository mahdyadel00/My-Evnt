<?php

namespace App\Jobs;

use App\Models\Event;
use App\Mail\EventReminderEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEventReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Event $event;
    protected $users;
    public function __construct(Event $event, $users)
    {
        $this->event = $event;
        $this->users = $users;
    }

    public function handle(): void
    {
        // Determine next relevant event date (earliest upcoming)
        $eventDate = optional($this->event->eventDates()->orderBy('start_date')->first())->start_date;

        foreach ($this->users as $user) {
            try {
                Mail::to($user->email)->queue(new EventReminderEmail($this->event, $user, $eventDate));
            } catch (\Throwable $e) {
                Log::channel('error')->error('Failed sending reminder email', [
                    'event_id' => $this->event->id ?? null,
                    'user_id' => $user->id ?? null,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }
}
