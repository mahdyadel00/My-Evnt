<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
class AdFee extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }


    public function events()
    {
        return $this->hasOne(Event::class);
    }
}
