<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Normalize Egyptian phone numbers to E.164 (+20…) or international digits for messaging APIs.
 */
class PhoneNormalizer
{
    public const DEFAULT_COUNTRY_CODE = '20';

    /**
     * E.164 with leading plus (e.g. +201234567890).
     */
    public static function toE164(string $phone, string $countryCode = self::DEFAULT_COUNTRY_CODE): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';
        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        if (! str_starts_with($digits, $countryCode)) {
            $digits = $countryCode.$digits;
        }

        return '+'.$digits;
    }

    /**
     * Digits only with country code — for WAAPI (e.g. 201234567890).
     */
    public static function toInternationalDigits(string $phone, string $countryCode = self::DEFAULT_COUNTRY_CODE): string
    {
        $e164 = self::toE164($phone, $countryCode);

        return $e164 !== '' ? substr($e164, 1) : '';
    }
}
