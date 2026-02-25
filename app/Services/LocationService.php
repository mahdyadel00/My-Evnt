<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\City;
use App\Models\Country;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LocationService
{
    /**
     * Get user location from IP address using MaxMind GeoIP2
     *
     * @param string|null $ip
     * @return array
     */
    public function getLocationFromIp(?string $ip = null): array
    {
        // Get IP if not provided
        if (!$ip) {
            $ip = $this->getUserIp();
        }

        // Skip localhost IPs
        if ($this->isLocalIp($ip)) {
            return $this->getDefaultLocation();
        }

        // Try to get from cache first
        $cacheKey = "location:ip:{$ip}";

        return Cache::remember($cacheKey, 86400, function () use ($ip) {
            return $this->fetchLocationFromMaxMind($ip);
        });
    }

    /**
     * Fetch location from MaxMind GeoIP2 database
     *
     * @param string $ip
     * @return array
     */
    private function fetchLocationFromMaxMind(string $ip): array
    {
        try {
            // Path to GeoLite2-City database
            $databasePath = storage_path('app/geoip/GeoLite2-City.mmdb');
            
            // Check if database file exists
            if (!file_exists($databasePath)) {
                Log::warning("MaxMind database not found at: {$databasePath}. Falling back to API.");
                return $this->fetchLocationFromApi($ip);
            }

            $reader = new Reader($databasePath);
            $record = $reader->city($ip);

            // Extract location data
            $countryCode = $record->country->isoCode;
            $countryName = $record->country->name;
            $cityName = $record->city->name;
            $latitude = $record->location->latitude;
            $longitude = $record->location->longitude;
            $regionName = $record->mostSpecificSubdivision->name ?? null;
            $postalCode = $record->postal->code ?? null;

            // Try to find country in database
            $country = null;
            if ($countryCode) {
                $country = Country::where('code', $countryCode)
                    ->where('is_available', true)
                    ->first();
            }

            // Try to find city in database
            $city = null;
            if ($country && $cityName) {
                // Try exact match first (case insensitive)
                $city = City::where('country_id', $country->id)
                    ->whereRaw('LOWER(name) = LOWER(?)', [$cityName])
                    ->where('is_available', true)
                    ->first();

                // If exact match not found, try LIKE
                if (!$city) {
                    $city = City::where('country_id', $country->id)
                        ->where('name', 'LIKE', "%{$cityName}%")
                        ->where('is_available', true)
                        ->first();
                }
            }

            return [
                'ip' => $ip,
                'country_code' => $countryCode,
                'country_name' => $countryName,
                'city_name' => $cityName,
                'region_name' => $regionName,
                'postal_code' => $postalCode,
                'country' => $country,
                'city' => $city,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'detected' => true,
                'source' => 'maxmind'
            ];

        } catch (AddressNotFoundException $e) {
            Log::warning("IP address not found in MaxMind database: {$ip}. Falling back to API.");
            return $this->fetchLocationFromApi($ip);
        } catch (\Exception $e) {
            Log::error("MaxMind GeoIP2 Error: " . $e->getMessage() . " | IP: {$ip}");
            return $this->fetchLocationFromApi($ip);
        }
    }

    /**
     * Fallback: Fetch location from IP API (if MaxMind fails)
     *
     * @param string $ip
     * @return array
     */
    private function fetchLocationFromApi(string $ip): array
    {
        try {
            // Using ip-api.com as fallback (free, no API key required, 45 requests/minute)
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get("http://ip-api.com/json/{$ip}", [
                'fields' => 'status,country,countryCode,city,lat,lon,regionName,zip'
            ]);

            if ($response->successful() && $data = $response->json()) {
                if (isset($data['status']) && $data['status'] === 'success') {
                    return $this->mapApiDataToLocation($data);
                }
            }

            Log::warning("Failed to get location from IP API: {$ip}");
        } catch (\Exception $e) {
            Log::error("LocationService API Error: " . $e->getMessage());
        }

        return $this->getDefaultLocation();
    }

    /**
     * Map API data to our location format
     *
     * @param array $data
     * @return array
     */
    private function mapApiDataToLocation(array $data): array
    {
        $countryCode = $data['countryCode'] ?? null;
        $cityName = $data['city'] ?? null;
        $countryName = $data['country'] ?? null;

        // Try to find country in database
        $country = null;
        if ($countryCode) {
            $country = Country::where('code', $countryCode)
                ->where('is_available', true)
                ->first();
        }

        // Try to find city in database
        $city = null;
        if ($country && $cityName) {
            $city = City::where('country_id', $country->id)
                ->where('name', 'LIKE', "%{$cityName}%")
                ->where('is_available', true)
                ->first();

            // If exact match not found, try to find closest match
            if (!$city) {
                $city = City::where('country_id', $country->id)
                    ->where('is_available', true)
                    ->orderBy('name')
                    ->first();
            }
        }

        return [
            'ip' => request()->ip(),
            'country_code' => $countryCode,
            'country_name' => $countryName,
            'city_name' => $cityName,
            'country' => $country,
            'city' => $city,
            'latitude' => $data['lat'] ?? null,
            'longitude' => $data['lon'] ?? null,
            'detected' => true,
            'source' => 'api_fallback'
        ];
    }

    /**
     * Get user's IP address
     *
     * @return string
     */
    public function getUserIp(): string
    {
        // Check for common proxy headers
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR'
        ];

        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];

                // Handle comma-separated IPs (from proxies)
                if (strpos($ip, ',') !== false) {
                    $ips = explode(',', $ip);
                    $ip = trim($ips[0]);
                }

                return $ip;
            }
        }

        return request()->ip() ?? '0.0.0.0';
    }

    /**
     * Check if IP is local/private
     *
     * @param string $ip
     * @return bool
     */
    private function isLocalIp(string $ip): bool
    {
        $localPatterns = [
            '127.0.0.1',
            'localhost',
            '::1',
            '0.0.0.0'
        ];

        if (in_array($ip, $localPatterns)) {
            return true;
        }

        // Check if it's a private IP range
        return !filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }

    /**
     * Get default location (when IP detection fails)
     *
     * @return array
     */
    private function getDefaultLocation(): array
    {
        // Try to get Egypt (Cairo) as default
        $defaultCountry = Country::where('code', 'EG')->first();
        $defaultCity = null;

        if ($defaultCountry) {
            $defaultCity = City::where('country_id', $defaultCountry->id)
                ->where('name', 'Cairo')
                ->first();
        }

        return [
            'ip' => request()->ip(),
            'country_code' => $defaultCountry?->code ?? null,
            'country_name' => $defaultCountry?->name ?? null,
            'city_name' => $defaultCity?->name ?? 'Location',
            'country' => $defaultCountry,
            'city' => $defaultCity,
            'latitude' => null,
            'longitude' => null,
            'detected' => false,
            'source' => 'default'
        ];
    }

    /**
     * Get user location for display
     *
     * @return string
     */
    public function getUserLocation(): string
    {
        // If user is logged in and has a city, use it
        if (auth()->check() && auth()->user()->city) {
            return auth()->user()->city->name;
        }

        // Try to get from session
        if (session()->has('user_location')) {
            return session('user_location');
        }

        // Get from IP
        $location = $this->getLocationFromIp();

        if ($location['city']) {
            $cityName = $location['city']->name;
            session(['user_location' => $cityName]);
            return $cityName;
        }

        if ($location['city_name']) {
            session(['user_location' => $location['city_name']]);
            return $location['city_name'];
        }

        return 'Location';
    }

    /**
     * Clear location cache
     *
     * @param string|null $ip
     * @return void
     */
    public function clearLocationCache(?string $ip = null): void
    {
        if (!$ip) {
            $ip = $this->getUserIp();
        }

        Cache::forget("location:ip:{$ip}");
    }
}