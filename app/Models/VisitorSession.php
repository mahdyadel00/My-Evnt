<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\LocationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id', 'user_id', 'event_id', 'ip_address', 'user_agent',
        'device', 'browser', 'os', 'country', 'city',
        'referrer', 'page_views', 'visited_at', 'last_activity'
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'last_activity' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get location details from IP address
     *
     * @return array
     */
    public function getLocationFromIp(): array
    {
        if (!$this->ip_address) {
            return $this->getDefaultLocation();
        }

        $locationService = app(LocationService::class);
        return $locationService->getLocationFromIp($this->ip_address);
    }

    /**
     * Get country name from IP (with fallback to stored value)
     *
     * @return string|null
     */
    public function getCountryFromIp(): ?string
    {
        // If country is already stored, return it
        if ($this->country) {
            return $this->country;
        }

        // Otherwise, try to get from IP
        $location = $this->getLocationFromIp();
        return $location['country_name'] ?? $location['country']?->name ?? null;
    }

    /**
     * Get city name from IP (with fallback to stored value)
     *
     * @return string|null
     */
    public function getCityFromIp(): ?string
    {
        // If city is already stored, return it
        if ($this->city) {
            return $this->city;
        }

        // Otherwise, try to get from IP
        $location = $this->getLocationFromIp();
        return $location['city_name'] ?? $location['city']?->name ?? null;
    }

    /**
     * Get default location when IP lookup fails
     *
     * @return array
     */
    private function getDefaultLocation(): array
    {
        return [
            'country_name' => null,
            'city_name' => null,
            'country' => null,
            'city' => null,
        ];
    }
}