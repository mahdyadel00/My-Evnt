<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Observers\AuditObserver;
use App\Traits\OrderByLatest;
use Illuminate\Support\Collection;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles, OrderByLatest;
    protected $observables = ['logging'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['city_id', 'country_id', 'category_id', 'sub_category_id', 'first_name', 'middle_name', 'last_name', 'user_name', 'email', 'password', 'phone', 'phone_verified_at', 'email_verified_at', 'api_token', 'about', 'last_login', 'login_count', 'address', 'birth_date', 'social_id', 'social_type', 'code', 'is_active', 'fcm_token', 'roles_name' , 'type'];

    protected $softDelete = true;

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
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'roles_name' => 'array',
    ];

    protected $appends = ['full_name'];

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value;
        $this->attributes['user_name'] = explode('@', $value)[0];
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function getFullNameAttribute()
    {
        $fullName = "{$this->attributes['first_name']}";

        if (array_key_exists('middle_name', $this->attributes)) {
            $fullName .= " {$this->attributes['middle_name']}";
        }

        if (array_key_exists('last_name', $this->attributes)) {
            $fullName .= " {$this->attributes['last_name']}";
        }
        return $fullName;
    }

    public function getFullPhoneAttribute()
    {
        if ($this->phone()->get() < 2) {
            return "{$this->phone()->first()->country_code} {$this->phone()->first()->phone}";
        }
    }

    //following company
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_followers');
    }

    public function favourites()
    {
        return $this->hasMany(EventFavourite::class, 'user_id');
    }

    public function interestedEvents()
    {
        return $this->hasMany(EventInterested::class, 'user_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_users');
    }

    //    public function tickets()
    //    {
    //        return $this->hasMany(Ticket::class);
    //    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function eventUsers()
    {
        return $this->hasMany(EventUser::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function userTickets()
    {
        return $this->belongsToMany(Ticket::class, 'user_tickets');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function category()
    {
        return $this->belongsToMany(EventCategory::class, 'category_users');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Minimum national / international digit count to treat a phone as valid for messaging pickers.
     */
    private const OUTBOUND_MESSAGING_MIN_PHONE_DIGITS = 10;

    /**
     * User has a non-empty display name and a phone line long enough to send SMS/WhatsApp.
     */
    public function isEligibleForOutboundMessaging(): bool
    {
        if (!$this->hasOutboundMessagingDisplayName()) {
            return false;
        }

        $digits = preg_replace('/\D/u', '', (string) ($this->getAttribute('phone') ?? ''));

        return strlen($digits) >= self::OUTBOUND_MESSAGING_MIN_PHONE_DIGITS;
    }

    /**
     * Whether first / middle / last name produce a non-empty label (excludes placeholder-only rows).
     */
    public function hasOutboundMessagingDisplayName(): bool
    {
        $parts = array_filter(
            [
                trim((string) ($this->getAttribute('first_name') ?? '')),
                trim((string) ($this->getAttribute('middle_name') ?? '')),
                trim((string) ($this->getAttribute('last_name') ?? '')),
            ],
            static fn (string $part): bool => $part !== ''
        );

        return implode(' ', $parts) !== '';
    }

    /**
     * Key used to treat one phone line as one recipient (digits only; users without phone stay distinct by id).
     */
    private static function outboundMessagingPhoneKey(self $user): string
    {
        $digits = preg_replace('/\D/u', '', (string) ($user->getAttribute('phone') ?? ''));

        return $digits !== '' ? $digits : 'nophone-' . $user->id;
    }

    /**
     * Users for admin SMS/WhatsApp picker: one row per phone number (lowest user id kept).
     *
     * @return Collection<int, self>
     */
    public static function collectionForMessagingPicker(): Collection
    {
        return static::query()
            ->orderBy('id')
            ->get()
            ->filter(static fn (self $user): bool => $user->isEligibleForOutboundMessaging())
            ->unique(static fn (self $user): string => self::outboundMessagingPhoneKey($user))
            ->values();
    }

    /**
     * Drop duplicate phone lines so the same number is not messaged twice in one batch.
     *
     * @param Collection<int, self> $users
     *
     * @return Collection<int, self>
     */
    public static function dedupeForMessaging(Collection $users): Collection
    {
        return $users
            ->unique(static fn (self $user): string => self::outboundMessagingPhoneKey($user))
            ->values();
    }
}
