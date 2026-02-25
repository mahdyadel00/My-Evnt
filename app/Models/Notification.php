<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
class Notification extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    protected $fillable = [
        'user_id',
        'company_id',
        'title',
        'message',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id'                => 'integer',
        'user_id'           => 'integer',
        'company_id'        => 'integer',
        'title'             => 'string',
        'message'           => 'string',
        'status'            => 'string',
    ];
    /**
     * The attributes that should be appended to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'created_at',
        'updated_at',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


}
