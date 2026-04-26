<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Channel for admin-initiated user messages (SMS Misr vs WhatsApp providers).
 */
enum OutboundMessageChannel: string
{
    case Whatsapp = 'whatsapp';

    case Sms = 'sms';

    /**
     * Values accepted by {@see \App\Services\SmsService::sendCustomMessage()}.
     */
    public function toSmsServiceType(): string
    {
        return $this->value;
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
