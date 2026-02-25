<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
class Ticket extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    protected $fillable = [
        'event_id',
        'event_date_id',
        'ticket_type',
        'price',
        'quantity',
        'qr_code',
    ];

    protected $casts = [
        // 'price' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }

    public function scopeOwnedBy($query, $userId)
    {
        return $query->whereHas('users', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }


    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ticketQr()
    {
        return $this->hasOne(TicketQr::class);
    }

    public function userTickets()
    {
        return $this->belongsToMany(User::class, 'user_tickets');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_tickets');
    }


    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // في موديل Ticket
    public function eventDate()
    {
        return $this->belongsTo(EventDate::class);
    }
}
