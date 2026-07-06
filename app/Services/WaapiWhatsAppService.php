<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * WAAPI (waapi.octopusteam.net) — send WhatsApp text messages.
 *
 * Uses POST /api/send-message with authkey header + JSON (auto device routing).
 *
 * @see https://github.com/octopus-software-team/waapi-laravel
 */
class WaapiWhatsAppService
{
    /**
     * @return array{success: bool, message?: string, response?: mixed, http_code?: int}
     */
    public function sendText(string $phoneNumber, string $message): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'WAAPI is not configured. Set WAAPI_AUTH_KEY in .env (from your WAAPI dashboard).',
            ];
        }

        $phone = $this->formatPhone($phoneNumber);
        if ($phone === '') {
            return [
                'success' => false,
                'message' => 'Invalid phone number',
            ];
        }

        $authKey = trim((string) config('services.waapi.auth_key', ''));

        try {
            Log::info('WAAPI: sending message', [
                'phone' => $phone,
                'message_length' => strlen($message),
            ]);

            return $this->sendViaAutoDevice($authKey, $phone, $message);
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
        $authKey = trim((string) config('services.waapi.auth_key', ''));

        return $authKey !== '';
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

    /**
     * @return array{success: bool, message?: string, response?: mixed, http_code?: int}
     */
    private function sendViaAutoDevice(string $authKey, string $phone, string $message): array
    {
        $endpoint = $this->apiBaseUrl().'/send-message';
        $payload = json_encode([
            'to' => $phone,
            'type' => 'text',
            'message' => $message,
        ], JSON_UNESCAPED_UNICODE);

        return $this->executeCurlRequest(
            $endpoint,
            [
                'Accept: application/json',
                'Content-Type: application/json',
                'authkey: '.$authKey,
            ],
            $payload,
            $phone
        );
    }

    /**
     * @param  array<int, string>  $headers
     * @return array{success: bool, message?: string, response?: mixed, http_code?: int}
     */
    private function executeCurlRequest(string $endpoint, array $headers, string $payload, string $phone): array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $raw = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            Log::error('WAAPI cURL error', [
                'error' => $curlError,
                'http_code' => $httpCode,
                'phone' => $phone,
            ]);

            return [
                'success' => false,
                'message' => 'WAAPI connection error: '.$curlError,
                'http_code' => $httpCode,
            ];
        }

        return $this->interpretResponse($httpCode, (string) $raw, $phone);
    }

    private function apiBaseUrl(): string
    {
        $configured = rtrim(trim((string) config('services.waapi.api_url', '')), '/');

        if ($configured === '') {
            return 'https://waapi.octopusteam.net/api';
        }

        if (str_ends_with($configured, '/create-message')) {
            return rtrim(substr($configured, 0, -strlen('/create-message')), '/');
        }

        if (str_ends_with($configured, '/send-message')) {
            return rtrim(substr($configured, 0, -strlen('/send-message')), '/');
        }

        if (str_ends_with($configured, '/api')) {
            return $configured;
        }

        return $configured.'/api';
    }

    /**
     * @return array{success: bool, message?: string, response?: mixed, http_code?: int}
     */
    private function interpretResponse(int $httpCode, string $raw, string $phone): array
    {
        $body = json_decode($raw, true);

        Log::info('WAAPI API response', [
            'http_code' => $httpCode,
            'response' => is_array($body) ? $body : substr($raw, 0, 500),
            'phone' => $phone,
        ]);

        if ($httpCode >= 200 && $httpCode < 300) {
            $responseId = is_array($body)
                ? ($body['data']['message_id'] ?? $body['message_id'] ?? $body['id'] ?? null)
                : null;

            return [
                'success' => true,
                'response' => $responseId ?? 'OK',
                'message' => 'WhatsApp message sent via WAAPI',
                'http_code' => $httpCode,
            ];
        }

        if ($httpCode === 409 && is_array($body)) {
            return [
                'success' => false,
                'message' => ($body['error'] ?? 'No connected WhatsApp device in WAAPI. Open your WAAPI dashboard and connect/scan a device.').' (HTTP: 409)',
                'response' => $body,
                'http_code' => $httpCode,
            ];
        }

        if (! is_array($body)) {
            return [
                'success' => false,
                'message' => 'WAAPI request failed (HTTP: '.$httpCode.')',
                'http_code' => $httpCode,
            ];
        }

        $errorMessage = 'WAAPI returned error';
        if (isset($body['error']) && is_string($body['error'])) {
            $errorMessage = $body['error'];
        } elseif (isset($body['message']) && is_string($body['message'])) {
            $errorMessage = $body['message'];
        } elseif (isset($body['errors']) && is_array($body['errors'])) {
            $errorMessage = implode(', ', $body['errors']);
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
