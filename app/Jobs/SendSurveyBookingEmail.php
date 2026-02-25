<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\SurveyBookingMail;
use App\Models\FormServay;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendSurveyBookingEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public FormServay $survey
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Load relationships
            $this->survey->load('event', 'event.company', 'event.city', 'event.category');

            // Send email
            Mail::to($this->survey->email)->send(new SurveyBookingMail($this->survey));

            Log::info('Survey booking email sent successfully via job', [
                'survey_id' => $this->survey->id,
                'email' => $this->survey->email,
                'customer' => $this->survey->first_name . ' ' . $this->survey->last_name
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send survey booking email via job', [
                'survey_id' => $this->survey->id,
                'email' => $this->survey->email,
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            // Re-throw to mark job as failed
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Survey booking email job failed permanently', [
            'survey_id' => $this->survey->id,
            'email' => $this->survey->email,
            'error' => $exception->getMessage()
        ]);
    }
}
