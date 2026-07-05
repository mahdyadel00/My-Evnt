<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * WAAPI (waapi.octopusteam.net) — send WhatsApp text messages.
 *
 * @see https://github.com/octopus-software-team/waapi-laravel — POST /api/create-message with appkey + authkey
 */
class WaapiWhatsAppService
{
    /**
     * @return array{success: bool, message?: string, response?: mixed, http_code?: int}
     */
    public function sendText(string $phoneNumber, string $message): array
    {
        $appKey = trim((string) config('services.waapi.app_key', ''));
        $authKey = trim((string) config('services.waapi.auth_key', ''));
        $endpoint = $this->resolveEndpoint();

        if ($appKey === '' || $authKey === '') {
            return [
                'success' => false,
                'message' => 'WAAPI is not configured. Set WAAPI_APP_KEY and WAAPI_AUTH_KEY in .env (from your WAAPI dashboard).',
            ];
        }

        $phone = $this->formatPhone($phoneNumber);
        if ($phone === '') {
            return [
                'success' => false,
                'message' => 'Invalid phone number',
            ];
        }

        try {
            Log::info('WAAPI: sending message', [
                'endpoint' => $endpoint,
                'phone' => $phone,
                'message_length' => strlen($message),
            ]);

            $httpResponse = Http::timeout(30)
                ->acceptJson()
                ->asForm()
                ->post($endpoint, [
                    'appkey' => $appKey,
                    'authkey' => $authKey,
                    'to' => $phone,
                    'message' => $message,
                    'sandbox' => false,
                ]);

            return $this->interpretResponse($httpResponse, $phone);
        } catch (\Throwable $e) {
            Log::error('WAAPI exception: '.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return [
                'success' => false,
                'message' => 'WAAPI error: '.$e->getMessage(),
            ];
        }
    }

    public function isConfigured(): bool
    {
        $appKey = trim((string) config('services.waapi.app_key', ''));
        $authKey = trim((string) config('services.waapi.auth_key', ''));

        return $appKey !== '' && $authKey !== '';
    }

    /**
     * International digits without "+" (e.g. 201234567890).
     */
    public function formatPhone(string $phoneNumber): string
    {
        $digits = preg_replace('/[^0-9]/', '', $phoneNumber) ?? '';
        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (! str_starts_with($digits, '20') && strlen($digits) <= 11) {
            $digits = '20'.$digits;
        }

        return $digits;
    }

    private function resolveEndpoint(): string
    {
        $configured = rtrim(trim((string) config('services.waapi.api_url', '')), '/');

        if ($configured === '') {
            return 'https://waapi.octopusteam.net/api/create-message';
        }

        if (str_ends_with($configured, '/create-message')) {
            return $configured;
        }

        return $configured.'/api/create-message';
    }

    /**
     * @return array{success: bool, message?: string, response?: mixed, http_code?: int}
     */
    private function interpretResponse(\Illuminate\Http\Client\Response $httpResponse, string $phone): array
    {
        $httpCode = $httpResponse->status();
        $body = $httpResponse->json();
        $raw = $httpResponse->body();

        Log::info('WAAPI API response', [
            'http_code' => $httpCode,
            'response' => is_array($body) ? $body : substr($raw, 0, 500),
            'phone' => $phone,
        ]);

        if ($httpResponse->successful()) {
            $responseId = is_array($body)
                ? ($body['message_id'] ?? $body['id'] ?? ($body['data']['id'] ?? null))
                : null;

            return [
                'success' => true,
                'response' => $responseId ?? 'OK',
                'message' => 'WhatsApp message sent via WAAPI',
                'http_code' => $httpCode,
            ];
        }

        if (! is_array($body)) {
            Log::error('WAAPI: non-JSON error response', [
                'http_code' => $httpCode,
                'raw' => substr($raw, 0, 500),
                'phone' => $phone,
            ]);

            return [
                'success' => false,
                'message' => 'WAAPI request failed (HTTP: '.$httpCode.')',
                'http_code' => $httpCode,
            ];
        }

        $errorMessage = 'WAAPI returned error';
        if (isset($body['message']) && is_string($body['message'])) {
            $errorMessage = $body['message'];
        } elseif (isset($body['error']) && is_string($body['error'])) {
            $errorMessage = $body['error'];
        } elseif (isset($body['errors']) && is_array($body['errors'])) {
            $errorMessage = json_encode($body['errors'], JSON_UNESCAPED_UNICODE);
        }

        Log::error('WAAPI send failed', [
            'http_code' => $httpCode,
            'response' => $body,
            'phone' => $phone,
        ]);

        return [
            'success' => false,
            'message' => $errorMessage.' (HTTP: '.$httpCode.')',
            'response' => $body,
            'http_code' => $httpCode,
        ];
    }
}
