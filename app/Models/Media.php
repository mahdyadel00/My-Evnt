<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
class Media extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'path',
        'post_url',
        'type',
        'order',
        'size',
        'is_main',
        'mediable_id',
        'mediable_type',
    ];

    protected $casts = [
        'order'   => 'integer',
        'is_main' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
    }

    public function mediable()
    {
        return $this->morphTo();
    }
}
