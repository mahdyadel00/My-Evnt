<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
/**
 * SocialGallery Model
 *
 * Manages social media gallery images for the website footer
 *
 * @property int $id
 * @property string $title
 * @property string $instagram_handle
 * @property string $instagram_link
 * @property boolean $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class SocialGallery extends Model
{
    use HasFactory;

    protected $observables = ['logging'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'instagram_handle',
        'instagram_link',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Get all media for the social gallery.
     * Since we have 6 fixed images, we'll handle them through media relationship
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Get active social gallery record
     *
     * @return \Illuminate\Database\Eloquent\Model|static|null
     */
    public static function getActive()
    {
        return static::with('media')->where('status', true)->first();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        static::observe(AuditObserver::class);
        // Ensure only one record is active at a time
        static::saving(function ($model) {
            if ($model->status) {
                static::where('status', true)->where('id', '!=', $model->id)->update(['status' => false]);
            }
        });
    }
}
