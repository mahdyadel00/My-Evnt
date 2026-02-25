<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\SendEventReminderEmail;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Console\Commands\SendReminderEmails;
class Kernel extends ConsoleKernel
{

    protected $commands = [
        SendReminderEmails::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->call(function () {
        //     Log::info('Checking events for reminders at: ' . now());

        //     $tomorrow = now()->addDay()->toDateString();
        //     $events = Event::with(['eventUsers.user', 'eventDates'])
        //         ->whereHas('eventDates', function ($query) use ($tomorrow) {
        //             $query->whereDate('start_date', $tomorrow);
        //         })
        //         ->get();

        //     Log::info('Events found: ' . $events->count());

        //     foreach ($events as $event) {
        //         $users = $event->eventUsers
        //             ->pluck('user')
        //             ->filter()
        //             ->unique('email')
        //             ->values();

        //         if ($users->isEmpty()) {
        //             Log::info('No registered users for event: ' . $event->name);
        //             continue;
        //         }

        //         dispatch(new SendEventReminderEmail($event, $users));
        //         Log::info('Reminder dispatched for event: ' . $event->name . ' to ' . $users->count() . ' users');
        //     }
        // })->dailyAt('10:00');

        $schedule->command('reminder:email')->dailyAt('12:11');

    }


    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
