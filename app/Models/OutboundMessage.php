<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OutboundMessageStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $batch_id
 * @property int|null $user_id
 * @property string $phone
 * @property string $message
 * @property string $channel
 * @property OutboundMessageStatus $status
 * @property int $attempts
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property \Illuminate\Support\Carbon|null $sent_at
 * @property string|null $provider_response_id
 * @property string|null $error_message
 * @property string|null $source
 */
class OutboundMessage extends Model
{
    protected $fillable = [
        'batch_id',
        'user_id',
        'phone',
        'message',
        'channel',
        'status',
        'attempts',
        'scheduled_at',
        'sent_at',
        'provider_response_id',
        'error_message',
        'source',
    ];

    protected $casts = [
        'status' => OutboundMessageStatus::class,
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'attempts' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeReadyToSend(Builder $query): Builder
    {
        return $query
            ->where(function (Builder $q): void {
                $q->where('status', OutboundMessageStatus::Pending)
                    ->orWhere(function (Builder $paused): void {
                        $paused->where('status', OutboundMessageStatus::Paused)
                            ->where('scheduled_at', '<=', now());
                    });
            })
            ->where(function (Builder $q): void {
                $q->whereNull('scheduled_at')
                    ->orWhere('scheduled_at', '<=', now());
            });
    }

    public function markSent(?string $providerResponseId = null): void
    {
        $this->update([
            'status' => OutboundMessageStatus::Sent,
            'sent_at' => now(),
            'provider_response_id' => $providerResponseId,
            'error_message' => null,
        ]);
    }

    public function markFailed(string $errorMessage): void
    {
        $this->update([
            'status' => OutboundMessageStatus::Failed,
            'error_message' => $errorMessage,
        ]);
    }

    public function markPaused(string $errorMessage, int $cooldownMinutes): void
    {
        $this->update([
            'status' => OutboundMessageStatus::Paused,
            'scheduled_at' => now()->addMinutes($cooldownMinutes),
            'error_message' => $errorMessage,
        ]);
    }
}
