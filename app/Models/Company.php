<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\OrderByLatest;
use App\Observers\AuditObserver;
class Company extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, OrderByLatest;
    protected $observables = ['logging'];
    protected $fillable = ['first_name', 'last_name', 'user_name', 'company_name', 'email', 'phone', 'whats_app', 'password', 'website', 'facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'snapchat', 'tiktok', 'status', 'description', 'address', 'type', 'api_token', 'city_id', 'country_id'];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function events()
    {
        // Add this function
        return $this->hasMany(Event::class);
    }

    public function archives()
    {
        return $this->hasMany(Archive::class);
    }

    public function followers()
    {
        return $this->hasMany(CompanyFollower::class, 'company_id');
    }
    // public function followers()
    // {
    //     return $this->belongsToMany(User::class, 'company_followers');
    // }
    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }
}
