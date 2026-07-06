<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Messaging\OutboundMessageQueueService;
use Illuminate\Console\Command;

class ProcessOutboundMessagesCommand extends Command
{
    protected $signature = 'outbound:process';

    protected $description = 'Process queued WhatsApp outbound messages in throttled batches (WAAPI)';

    public function handle(OutboundMessageQueueService $queueService): int
    {
        $stats = $queueService->processPendingBatch();

        if ($stats['skipped'] ?? false) {
            $this->info('Skipped: '.($stats['reason'] ?? 'unknown'));

            return self::SUCCESS;
        }

        $this->info(sprintf(
            'Processed batch — sent: %d, failed: %d, paused: %d',
            $stats['sent'],
            $stats['failed'],
            $stats['paused']
        ));

        return self::SUCCESS;
    }
}
