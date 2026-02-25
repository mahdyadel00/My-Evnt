<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

}
