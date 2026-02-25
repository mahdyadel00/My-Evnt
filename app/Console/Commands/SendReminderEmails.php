<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Mail\EventReminderEmail;
use App\Jobs\SendEventReminderEmail;
use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendReminderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:email {--test : Run in test mode (sends to first user only)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email Notification TO Users About Reminder Events (1 day before event)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isTest = $this->option('test');
        
        if ($isTest) {
            $this->info('🧪 Running in TEST mode...');
        }

        // Get tomorrow's date
        $tomorrow = Carbon::tomorrow()->toDateString();
        
        $this->info("📅 Looking for events happening on: {$tomorrow}");

        // Get all events that have event dates tomorrow
        $events = Event::with(['eventDates', 'orders.user'])
            ->whereHas('eventDates', function ($query) use ($tomorrow) {
                $query->whereDate('start_date', $tomorrow);
            })
            ->where('is_active', true)
            ->get();

        if ($events->isEmpty()) {
            $this->warn('⚠️  No events found for tomorrow.');
            return 0;
        }

        $this->info("✅ Found {$events->count()} event(s) for tomorrow.");

        $totalEmailsSent = 0;
        $totalEmailsFailed = 0;

        foreach ($events as $event) {
            $this->info("\n📋 Processing Event: {$event->name}");

            // Get the event date for tomorrow
            $eventDate = $event->eventDates()
                ->whereDate('start_date', $tomorrow)
                ->first();

            if (!$eventDate) {
                $this->warn("   ⚠️  No event date found for tomorrow for this event.");
                continue;
            }

            // Get all users who have orders for this event (with status checked or pending)
            $orders = Order::where('event_id', $event->id)
                ->whereIn('status', ['checked', 'pending'])
                ->with('user')
                ->get();

            // Get unique users with valid emails
            $users = $orders->pluck('user')
                ->filter()
                ->unique('id')
                ->filter(function ($user) {
                    return $user && $user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL);
                })
                ->values();

            if ($users->isEmpty()) {
                $this->warn("   ⚠️  No registered users found for this event.");
                continue;
            }

            $this->info("   👥 Found {$users->count()} registered user(s).");

            // In test mode, send to first user only
            if ($isTest) {
                $users = $users->take(1);
                $this->info("   🧪 TEST MODE: Sending to first user only.");
            }

            $emailsSent = 0;
            $emailsFailed = 0;

            foreach ($users as $user) {
                try {
                    // Send email directly (not queued) for immediate feedback
                    Mail::to($user->email)->send(
                        new EventReminderEmail($event, $user, $eventDate->start_date)
                    );

                    $emailsSent++;
                    $this->info("   ✅ Email sent to: {$user->email}");

                    // Small delay to avoid overwhelming mail server
                    if (!$isTest) {
                        usleep(200000); // 0.2 seconds
                    }

                } catch (\Exception $e) {
                    $emailsFailed++;
                    $this->error("   ❌ Failed to send email to: {$user->email} - {$e->getMessage()}");
                    
                    Log::error('Failed to send reminder email', [
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $totalEmailsSent += $emailsSent;
            $totalEmailsFailed += $emailsFailed;

            $this->info("   📊 Event Summary: {$emailsSent} sent, {$emailsFailed} failed");
        }

        $this->info("\n" . str_repeat('=', 50));
        $this->info("📊 Total Summary:");
        $this->info("   ✅ Emails Sent: {$totalEmailsSent}");
        $this->info("   ❌ Emails Failed: {$totalEmailsFailed}");
        $this->info(str_repeat('=', 50));

        return 0;
    }
}
