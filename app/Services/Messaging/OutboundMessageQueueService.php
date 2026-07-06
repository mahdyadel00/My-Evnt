<?php

declare(strict_types=1);

namespace App\Services\Messaging;

use App\Enums\OutboundMessageChannel;
use App\Enums\OutboundMessageStatus;
use App\Models\OutboundMessage;
use App\Models\User;
use App\Services\WaapiWhatsAppService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Queues and processes outbound WhatsApp messages in throttled batches (WAAPI rate limits).
 */
class OutboundMessageQueueService
{
    public function __construct(
        private readonly WaapiWhatsAppService $waapiWhatsAppService
    ) {
    }

    /**
     * @param  Collection<int, User>|iterable<int, User>  $users
     * @return array{batch_id: string, queued: int, skipped: int, failures: list<array{user_id: int, label: string, message: string}>}
     */
    public function enqueueBulk(iterable $users, string $message, string $channel, string $source = 'admin_users'): array
    {
        $batchId = (string) Str::uuid();
        $queued = 0;
        $failures = [];

        foreach ($users as $user) {
            $phone = trim((string) $user->phone);

            if ($phone === '') {
                $failures[] = [
                    'user_id' => $user->id,
                    'label' => $this->userLabel($user),
                    'message' => __('No phone number'),
                ];

                continue;
            }

            OutboundMessage::create([
                'batch_id' => $batchId,
                'user_id' => $user->id,
                'phone' => $phone,
                'message' => $message,
                'channel' => $channel,
                'status' => OutboundMessageStatus::Pending,
                'scheduled_at' => now(),
                'source' => $source,
            ]);

            $queued++;
        }

        Log::info('Outbound messages queued', [
            'batch_id' => $batchId,
            'channel' => $channel,
            'queued' => $queued,
            'skipped' => count($failures),
        ]);

        return [
            'batch_id' => $batchId,
            'queued' => $queued,
            'skipped' => count($failures),
            'failures' => $failures,
        ];
    }

    /**
     * Process a throttled batch of pending WhatsApp messages (called by cron).
     *
     * @return array{sent: int, failed: int, paused: int, skipped: bool, reason?: string}
     */
    public function processPendingBatch(): array
    {
        if ($this->hourlyLimitReached()) {
            Log::info('Outbound batch skipped: hourly WAAPI limit reached');

            return [
                'sent' => 0,
                'failed' => 0,
                'paused' => 0,
                'skipped' => true,
                'reason' => 'hourly_limit',
            ];
        }

        $batchSize = $this->batchSize();
        $delaySeconds = $this->delayBetweenSeconds();
        $maxAttempts = (int) config('services.waapi.throttle.max_attempts', 5);

        $messages = OutboundMessage::query()
            ->readyToSend()
            ->where('channel', OutboundMessageChannel::Whatsapp->value)
            ->orderBy('id')
            ->limit($batchSize)
            ->get();

        $stats = ['sent' => 0, 'failed' => 0, 'paused' => 0, 'skipped' => false];

        foreach ($messages as $outboundMessage) {
            if ($this->hourlyLimitReached()) {
                break;
            }

            $outboundMessage->update([
                'status' => OutboundMessageStatus::Processing,
                'attempts' => $outboundMessage->attempts + 1,
            ]);

            Log::info('Processing outbound message', [
                'id' => $outboundMessage->id,
                'batch_id' => $outboundMessage->batch_id,
                'phone' => $outboundMessage->phone,
                'attempt' => $outboundMessage->attempts,
            ]);

            $result = $this->waapiWhatsAppService->sendText(
                $outboundMessage->phone,
                $outboundMessage->message
            );

            if ($result['success'] ?? false) {
                $outboundMessage->markSent(
                    is_scalar($result['response'] ?? null) ? (string) $result['response'] : null
                );
                $stats['sent']++;

                if ($delaySeconds > 0) {
                    sleep($delaySeconds);
                }

                continue;
            }

            $errorMessage = (string) ($result['message'] ?? __('Unknown error'));

            if ($this->isRateLimited($result) && $outboundMessage->attempts < $maxAttempts) {
                $cooldown = $this->cooldownMinutes();
                $outboundMessage->markPaused($errorMessage, $cooldown);
                $stats['paused']++;

                Log::warning('Outbound message paused (WAAPI rate limit)', [
                    'id' => $outboundMessage->id,
                    'cooldown_minutes' => $cooldown,
                    'error' => $errorMessage,
                ]);

                break;
            }

            $outboundMessage->markFailed($errorMessage);
            $stats['failed']++;

            Log::error('Outbound message failed', [
                'id' => $outboundMessage->id,
                'error' => $errorMessage,
            ]);
        }

        return $stats;
    }

    /**
     * @param  array{success?: bool, message?: string, http_code?: int}  $result
     */
    private function isRateLimited(array $result): bool
    {
        $httpCode = (int) ($result['http_code'] ?? 0);
        $message = strtolower((string) ($result['message'] ?? ''));

        return $httpCode === 429
            || str_contains($message, 'cooldown')
            || str_contains($message, 'paused')
            || str_contains($message, 'rate limit')
            || str_contains($message, 'hard_block');
    }

    private function hourlyLimitReached(): bool
    {
        $maxPerHour = (int) config('services.waapi.throttle.max_per_hour', 50);

        if ($maxPerHour <= 0) {
            return false;
        }

        $sentLastHour = OutboundMessage::query()
            ->where('channel', OutboundMessageChannel::Whatsapp->value)
            ->where('status', OutboundMessageStatus::Sent)
            ->where('sent_at', '>=', now()->subHour())
            ->count();

        return $sentLastHour >= $maxPerHour;
    }

    private function batchSize(): int
    {
        return max(1, (int) config('services.waapi.throttle.batch_size', 5));
    }

    private function delayBetweenSeconds(): int
    {
        return max(0, (int) config('services.waapi.throttle.delay_between_seconds', 3));
    }

    private function cooldownMinutes(): int
    {
        return max(1, (int) config('services.waapi.throttle.cooldown_minutes', 15));
    }

    private function userLabel(User $user): string
    {
        $label = trim($user->user_name ?? '');

        if ($label === '') {
            $label = trim(($user->first_name ?? '').' '.($user->last_name ?? ''));
        }

        return $label !== '' ? $label : (string) $user->id;
    }
}
