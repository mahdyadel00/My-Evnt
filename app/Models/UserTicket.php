<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
class UserTicket extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    protected $fillable = [
        'user_id',
        'ticket_id',
        'quantity',
        ];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }

}
