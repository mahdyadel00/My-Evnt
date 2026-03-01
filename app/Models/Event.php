<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Observers\AuditObserver;
use App\Traits\OrderByLatest;

class Event extends Model
{
    use HasFactory , SoftDeletes , OrderByLatest;

    protected $observables = ['logging'];

    protected $fillable = [
        'uuid',
        'category_id',
        'sub_category_id',
        'city_id',
        'currency_id',
        'company_id', // Add this line
        'ad_fee_id',
        'name',
        'location',
        'latitude',
        'longitude',
        'description',
        'format',
        'address',
        'normal_price',
        'is_active',
        'cancellation_policy',
        'view_count',
        'upload_by',
        'organized_by',
        'external_link',
        'link_meeting',
        'facility',
        'is_exclusive',
        'summary',
        'area',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'format' => 'boolean',
    ];


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = Str::uuid()->toString();
            }
        });
        static::observe(AuditObserver::class);
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    //exclusive event
    public function scopeExclusive($query)
    {
        return $query->where('is_exclusive', true);
    }

    //top event
    public function scopeTop($query)
    {
        $today = now()->toDateString();
        return $query->whereHas('eventDates', function ($query) use ($today) {
            $query->where('start_date', '>=', $today)
                ->where('end_date', '>=', $today);
        })->orderByDesc('view_count');
    }

    public function scopeUpcoming($query)
    {
        $today = now()->toDateString();

        return $query->whereHas('eventDates', function ($query) use ($today) {
            $query->where('start_date', '>', $today)->where('end_date', '>', $today);
        })->orderByRaw('(SELECT MIN(start_date) FROM event_dates WHERE event_dates.event_id = events.id) ASC');
    }

    //event free
    public function scopeFree($query)
    {
        return $query->whereHas('tickets', function ($query) {
            $query->where('price', 0);
        });
    }

    //new event
    public function scopeNew($query)
    {
        $today = now()->toDateString();
        return $query->whereHas('eventDates', function ($query) use ($today) {
            $query->where('start_date', '>', $today)->orderByDesc('created_at');
        })->orderByDesc('created_at');
    }

    public function scopePlanOfMonth($query)
    {
        return $query->whereHas('eventDates', function ($query) {
            $query->whereMonth('start_date', Carbon::now()->month)->whereYear('start_date', Carbon::now()->year);
        });
    }

    /**
     * Get events by filter type (weekly)
     */
    public function scopeWeekly($query)
    {
        return $query->whereHas('eventDates', function ($query) {
            $query->where('start_date', Carbon::now()->week)
                  ->whereYear('start_date', Carbon::now()->year);
        });
    }
    

    /**
     * Get events by filter type (monthly)
     */
    public function scopeMonthly($query)
    {
        return $query->whereHas('eventDates', function ($query) {
            $query->whereMonth('start_date', Carbon::now()->month)
                  ->whereYear('start_date', Carbon::now()->year);
        });
    }

    /**
     * Get the status of this event
     */
    public function getStatusAttribute()
    {
        // If event has no dates, return 'no_dates'
        if ($this->eventDates->isEmpty()) {
            return 'no_dates';
        }

        $today = now()->toDateString();
        $hasUpcoming = false;
        $hasOngoing = false;
        $hasCompleted = false;

        foreach ($this->eventDates as $date) {
            if ($date->start_date > $today) {
                $hasUpcoming = true;
            } elseif ($date->start_date <= $today && $date->end_date >= $today) {
                $hasOngoing = true;
            } elseif ($date->end_date < $today) {
                $hasCompleted = true;
            }
        }

        // Priority: ongoing > upcoming > completed
        if ($hasOngoing) {
            return 'ongoing';
        } elseif ($hasUpcoming) {
            return 'upcoming';
        } else {
            return 'completed';
        }
    }

    /**
     * Get status label with color - simplified for new filter system
     */
    public function getStatusLabelAttribute()
    {
        $today = now()->toDateString();

        // Check if event is new (created in last 30 days)
        if ($this->created_at >= now()->subDays(30)) {
            $isNew = true;
        } else {
            $isNew = false;
        }

        // Check dates
        if ($this->eventDates->isEmpty()) {
            return ['text' => 'No Dates', 'class' => 'bg-warning'];
        }

        $hasUpcoming = $this->eventDates->where('start_date', '>', $today)->isNotEmpty();
        $hasOngoing = $this->eventDates->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)->isNotEmpty();
        $hasCompleted = $this->eventDates->where('end_date', '<', $today)->isNotEmpty();

        if ($hasOngoing) {
            return ['text' => 'Ongoing', 'class' => 'bg-success'];
        } elseif ($hasUpcoming) {
            if ($isNew) {
                return ['text' => 'New & Upcoming', 'class' => 'bg-info'];
            }
            return ['text' => 'Upcoming', 'class' => 'bg-primary'];
        } elseif ($hasCompleted) {
            return ['text' => 'Completed', 'class' => 'bg-secondary'];
        } else {
            return ['text' => 'Unknown', 'class' => 'bg-dark'];
        }
    }

    /**
     * Scope: only events whose last end_date (max of all event_dates) is still in the future (not ended).
     * So an event is hidden once all its dates have passed.
     */
    public function scopeNotEnded($query)
    {
        $today = now()->toDateString();
        $eventsTable = $this->getTable();
        $datesTable = (new EventDate())->getTable();

        return $query->whereHas('eventDates')
            ->whereRaw(
                "(SELECT MAX(ed.end_date) FROM {$datesTable} ed WHERE ed.event_id = {$eventsTable}.id AND ed.end_date IS NOT NULL) > ?",
                [$today]
            );
            
    }

    //past event
    public function scopePast($query)
    {
        $today = now()->toDateString();

        return $query->whereHas('eventDates', function ($query) use ($today) {
            $query->where('start_date', '<', $today);
        })->orderByRaw('(SELECT MAX(start_date) FROM event_dates WHERE event_dates.event_id = events.id) DESC');
  
    }
    // public function scopePast($query)
    // {
    //     $today = now()->toDateString();

    //     return $query->whereHas('eventDates', function ($query) use ($today) {
    //         $query->where('end_date', '<', $today);
    //     })->orderByRaw('(SELECT MAX(end_date) FROM event_dates WHERE event_dates.event_id = events.id) DESC');
    // }


    //event dates

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function company() // Add this function
    {
        return $this->belongsTo(Company::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Get event gallery images
     */
    public function gallery()
    {
        return $this->morphMany(Media::class, 'mediable')
            ->where('type', 'gallery')
            ->orderBy('order', 'asc');
    }

    /**
     * Get main gallery image
     */
    public function mainGalleryImage()
    {
        return $this->morphMany(Media::class, 'mediable')
            ->where('type', 'gallery')
            ->where('is_main', true)
            ->first();
    }

    public function favourites()
    {
        return $this->hasMany(EventFavourite::class, 'event_id');
    }

    public function interested()
    {
        return $this->hasMany(EventInterested::class, 'event_id');
    }


    // public function users()
    // {
    //     return $this->belongsToMany(User::class, 'event_favourites');
    // }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function archives()
    {
        return $this->hasMany(Archive::class);
    }

    public function eventUsers()
    {
        return $this->hasMany(EventUser::class);
    }

    public function adFee()
    {
        return $this->belongsTo(AdFee::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function eventDates()
    {
        return $this->hasMany(EventDate::class);
    }

    // public function transactions()
    // {
    //     return $this->hasMany(Transaction::class);
    // }

    public function sliders()
    {
        return $this->hasMany(Slider::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
