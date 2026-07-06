<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * Lifecycle status for queued outbound messages.
 */
enum OutboundMessageStatus: string
{
    case Pending = 'pending';

    case Processing = 'processing';

    case Sent = 'sent';

    case Failed = 'failed';

    case Paused = 'paused';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
