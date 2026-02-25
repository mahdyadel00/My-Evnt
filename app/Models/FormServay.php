<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormServay extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'session_type',
        'age_group',
        'quantity',
        'unit_price',
        'total_amount',
        'notes',
        'status',
        'date',
        'time',
        'address',
        'selected_address',
        'selected_location',
        'payment_method',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'date' => 'date',
        'time' => 'string',
        'quantity' => 'integer',
    ];

    /**
     * Get the event that owns the form survey.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Scope to filter by session type.
     */
    public function scopeBySessionType($query, $sessionType)
    {
        return $query->where('session_type', $sessionType);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for pending submissions.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for confirmed submissions.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Get session type label.
     */
    public function getSessionTypeLabelAttribute()
    {
        $sessionPricing = self::getSessionPricing();
        return $sessionPricing[$this->session_type]['title'] ?? 'Unknown Session';
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled'
        ];

        return $labels[$this->status] ?? 'Unknown Status';
    }

    /**
     * Calculate total revenue for a specific session type.
     */
    public static function getTotalRevenueBySessionType($sessionType)
    {
        return self::bySessionType($sessionType)
            ->where('status', 'confirmed')
            ->sum('total_amount');
    }

    /**
     * Get session pricing based on type.
     */
    public static function getSessionPricing()
    {
        return [
            'practice' => [
                'price' => 1000,
                'title' => 'Practice Session',
                'time' => '11:00 AM - 04:00 PM',
                'days' => 'Every Day',
                'note' => 'This is a practice session After the Beginner Surfing Course',
                'addresses' => [
                    'Hacienda Red',
                    'Dbay',
                ],
                'age_group' => [
                    'Adult' => [
                        'price' => 900,
                        'duration' => '11:00 AM - 05:00 PM',
                        'addresses' => [
                            'Hacienda Red',
                            'Dbay',
                        ],
                    ],
                    'Kids' => [
                        'price' => 1000,
                        'duration' => '11:00 AM - 05:00 PM',
                        'addresses' => [
                            'Hacienda Red',
                            'Dbay',
                        ],
                    ]
                ],
            ],
            'beginner' => [
                'price' => 8000,
                'title' => 'Beginner Surfing Course',
                'duration' => '4 hours',
                'days' => 'From Three Days To Four Days',
                'note' => 'This is a Beginner Surfing Course',
                'addresses' => [
                    'Hacienda Red',
                    'Dbay',
                ],
                'age_group' => [
                    'Adult' => [
                        'price' => 8000,
                        'duration' => '4 hours',
                        'addresses' => [
                            'Hacienda Red',
                            'Dbay',
                        ],
                    ],
                    'Kids' => [
                        'price' => 8000,
                        'duration' => '4 hours',
                        'addresses' => [
                            'Hacienda Red',
                            'Dbay',
                        ],
                    ]
                ],
            ]
        ];
    }
}
