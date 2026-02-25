<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
class TermsCondittion extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    protected $fillable = [
        'terms_condition',
        'privacy_policy',
        'about_us',
        'shipping_payment',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }

}
