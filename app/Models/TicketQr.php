<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
class TicketQr extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    protected $fillable = ['ticket_id', 'qr_code'];


    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }

    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }
}
