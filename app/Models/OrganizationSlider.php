<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
class OrganizationSlider extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    protected $fillable = ['title', 'description', 'video'];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }

}
