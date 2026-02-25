<?php

namespace App\Http\Middleware;

use App\Services\LocationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectUserLocation
{
    protected $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for authenticated users with city
        if (auth()->check() && auth()->user()->city_id) {
            return $next($request);
        }

        // Skip if location already detected in this session
        if (!session()->has('location_detected')) {
            $location = $this->locationService->getLocationFromIp();

            // Store in session
            session([
                'user_location' => $location['city_name'] ?? 'Location',
                'user_country' => $location['country_name'] ?? null,
                'user_city_id' => $location['city']?->id ?? null,
                'user_country_id' => $location['country']?->id ?? null,
                'location_detected' => true
            ]);
        }

        return $next($request);
    }
}

