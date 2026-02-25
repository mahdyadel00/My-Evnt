<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use App\Observers\AuditObserver;
class Order extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_id',
        'event_date_id',
        'payment_id',
        'order_number',
        'payment_status',
        'payment_reference',
        'payment_amount',
        'payment_currency',
        'payment_method',
        'payment_response',
        'total',
        'status',
        'quantity',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'quantity' => 'integer',
        'payment_response' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }

    /**
     * Get the user that owns the order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the event associated with the order
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the ticket associated with the order
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the event date associated with the order
     */
    public function eventDate(): BelongsTo
    {
        return $this->belongsTo(EventDate::class);
    }

    /**
     * Get order status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'badge-soft-warning',
            'checked' => 'badge-soft-info',
            'exited' => 'badge-soft-success',
            'cancelled' => 'badge-soft-danger',
            default => 'badge-soft-secondary',
        };
    }

    /**
     * Get formatted status name
     */
    public function getStatusNameAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'checked' => 'Checked In',
            'exited' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get payment status badge class
     */
    public function getPaymentStatusBadgeClassAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'badge-soft-warning',
            'completed' => 'badge-soft-success',
            'failed' => 'badge-soft-danger',
            'refunded' => 'badge-soft-info',
            default => 'badge-soft-secondary',
        };
    }

    /**
     * Get formatted order number
     */
    public function getFormattedOrderNumberAttribute(): string
    {
        return '#' . $this->order_number;
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format((float)$this->total, 2) . ' ' . ($this->payment_currency ?? 'جنيه');
    }

    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'checked']) && 
               $this->payment_status !== 'completed';
    }

    /**
     * Check if order can be deleted
     */
    public function canBeDeleted(): bool
    {
        return !($this->status === 'exited' && $this->payment_status === 'completed');
    }

    /**
     * Scope for filtering by status
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by payment status
     */
    public function scopeWithPaymentStatus($query, string $paymentStatus)
    {
        return $query->where('payment_status', $paymentStatus);
    }

    /**
     * Scope for orders in date range
     */
    public function scopeInDateRange($query, ?string $from = null, ?string $to = null)
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }
        
        return $query;
    }
}
