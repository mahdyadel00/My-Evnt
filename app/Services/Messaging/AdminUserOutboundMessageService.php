<?php

declare(strict_types=1);

namespace App\Services\Messaging;

use App\Enums\OutboundMessageChannel;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

/**
 * Sends a one-off message from the admin panel to a user's phone via SMS (SMS Misr) or WhatsApp (WAAPI / fallbacks).
 */
class AdminUserOutboundMessageService
{
    public function __construct(
        private readonly SmsService $smsService,
        private readonly OutboundMessageQueueService $outboundQueue
    ) {
    }

    /**
     * Send the same message to many users; CKEditor/HTML input is normalized to plain text for providers.
     *
     * @param iterable<int, User> $users
     *
     * @return array{
     *     sent: int,
     *     failed: int,
     *     failures: list<array{user_id: int, label: string, message: string}>,
     *     is_queued?: bool,
     *     queued?: int,
     *     batch_id?: string
     * }
     */
    public function sendBulk(iterable $users, string $message, OutboundMessageChannel $channel): array
    {
        $body = $this->normalizeMessageBody($message);

        if ($channel === OutboundMessageChannel::Whatsapp) {
            $result = $this->outboundQueue->enqueueAndProcessBulk($users, $body, $channel->value, 'admin_settings');
            $flash = $this->outboundQueue->buildFlashMessage($result);

            return [
                'is_queued' => true,
                'queued' => $result['queued'],
                'sent' => $result['dispatch']['sent'] ?? 0,
                'failed' => $result['skipped'] + ($result['dispatch']['failed'] ?? 0),
                'failures' => $result['failures'],
                'batch_id' => $result['batch_id'],
                'flash_type' => $flash['type'],
                'flash_message' => $flash['text'],
            ];
        }

        $sent = 0;
        $failures = [];

        foreach ($users as $user) {
            $sendResult = $this->sendToUser($user, $body, $channel, false);
            if ($sendResult['success'] ?? false) {
                $sent++;
            } else {
                $failures[] = [
                    'user_id' => $user->id,
                    'label' => trim($user->first_name.' '.$user->last_name) ?: (string) $user->id,
                    'message' => (string) ($sendResult['message'] ?? __('Unknown error')),
                ];
            }
        }

        return [
            'sent' => $sent,
            'failed' => count($failures),
            'failures' => $failures,
        ];
    }

    /**
     * Dispatch message to the user's stored phone number.
     *
     * @param bool $normalizeWhen true, strip HTML/rich text before sending (set false when body is already plain).
     *
     * @return array{success: bool, message?: string, response?: mixed}
     */
    public function sendToUser(User $user, string $message, OutboundMessageChannel $channel, bool $normalizeWhen = true): array
    {
        if ($normalizeWhen) {
            $message = $this->normalizeMessageBody($message);
        }

        $phone = trim((string) $user->phone);

        if ($phone === '') {
            return [
                'success' => false,
                'message' => __('This user has no phone number on file.'),
            ];
        }

        $result = $this->smsService->sendCustomMessage(
            $phone,
            $message,
            $channel->toSmsServiceType()
        );

        if (! ($result['success'] ?? false)) {
            Log::channel('error')->error('AdminUserOutboundMessageService: send failed', [
                'user_id' => $user->id,
                'channel' => $channel->value,
                'detail' => $result['message'] ?? 'unknown',
            ]);
        }

        return $result;
    }

    /**
     * Convert rich-text / HTML from CKEditor into a single plain-text body suitable for SMS and WhatsApp APIs.
     */
    private function normalizeMessageBody(string $message): string
    {
        $message = str_ireplace(['<br>', '<br/>', '<br />'], "\n", $message);
        $plain = strip_tags($message);
        $plain = html_entity_decode($plain, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/\R+/u', "\n", $plain));
    }
}
