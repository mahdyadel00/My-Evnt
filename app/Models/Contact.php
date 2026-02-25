<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
class Contact extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    protected $fillable = [
        'email',
        'phone',
        'subject',
        'message',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }
}
