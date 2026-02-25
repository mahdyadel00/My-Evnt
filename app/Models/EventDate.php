<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDate extends Model
{
    use HasFactory;


    protected $fillable = [
        'event_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        // Removed datetime cast for time fields to match database schema
        // 'start_time'    => 'datetime',
        // 'end_time'      => 'datetime',
    ];


    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
