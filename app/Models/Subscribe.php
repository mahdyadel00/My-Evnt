<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
class Subscribe extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    protected $fillable = [
        'email'
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }
}
