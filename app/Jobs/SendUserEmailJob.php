<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\UserEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
     * Job for sending a single email to one user.
 *
 * @package App\Jobs
 */
class SendUserEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @param int $userId   User ID to send email to
     * @param string $subject Email subject
     * @param string $message Email message content
     */
    public function __construct(
        public int $userId,
        public string $subject,
        public string $message
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $user = User::where('id', $this->userId)
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->first();

            if (!$user) {
                Log::warning('No user found to send email to', [
                    'user_id' => $this->userId,
                ]);
                return;
            }

            // Validate email address
            if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                Log::warning('Invalid email address', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                ]);
                return;
            }

            Mail::to($user->email)->send(new UserEmail($this->subject, $this->message));

            Log::info('User email sent successfully via job', [
                'user_id' => $user->id,
                'email'   => $user->email,
            ]);

            // Small delay to avoid overwhelming the mail server when using sync driver
            usleep(500000); // 0.5 second delay
        } catch (\Exception $e) {
            Log::error('User email job failed', [
                'user_id' => $this->userId,
                'error'   => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('User email job failed permanently', [
            'user_id' => $this->userId,
            'error'   => $exception->getMessage(),
            'trace'   => $exception->getTraceAsString(),
        ]);
    }
}

