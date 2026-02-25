<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    /**
     * Get all available countries
     *
     * @return JsonResponse
     */
    public function countries(): JsonResponse
    {
        $countries = Cache::remember('api:countries:list', 86400, function () {
            return Country::where('is_available', true)
                ->orderBy('name')
                ->get(['id', 'code', 'name']);
        });

        return response()->json([
            'success' => true,
            'data' => $countries,
            'count' => $countries->count()
        ]);
    }

    /**
     * Get cities for a specific country
     *
     * @param Country $country
     * @return JsonResponse
     */
    public function cities(Country $country): JsonResponse
    {
        if (!$country->is_available) {
            return response()->json([
                'success' => false,
                'message' => 'Country not available'
            ], 404);
        }

        $cities = Cache::remember("api:country:{$country->id}:cities", 86400, function () use ($country) {
            return $country->cities()
                ->where('is_available', true)
                ->orderBy('name')
                ->get(['id', 'name', 'country_id']);
        });

        return response()->json([
            'success' => true,
            'data' => $cities,
            'count' => $cities->count(),
            'country' => [
                'id' => $country->id,
                'name' => $country->name,
                'code' => $country->code
            ]
        ]);
    }

    /**
     * Get all cities (optional - for searching)
     *
     * @return JsonResponse
     */
    public function allCities(): JsonResponse
    {
        $cities = Cache::remember('api:cities:all', 86400, function () {
            return City::with('country:id,code,name')
                ->where('is_available', true)
                ->orderBy('name')
                ->get(['id', 'name', 'country_id']);
        });

        return response()->json([
            'success' => true,
            'data' => $cities,
            'count' => $cities->count()
        ]);
    }

    /**
     * Search cities by name
     *
     * @param string $query
     * @return JsonResponse
     */
    public function searchCities(string $query): JsonResponse
    {
        $cities = City::with('country:id,code,name')
            ->where('is_available', true)
            ->where('name', 'LIKE', "%{$query}%")
            ->orderBy('name')
            ->limit(50)
            ->get(['id', 'name', 'country_id']);

        return response()->json([
            'success' => true,
            'data' => $cities,
            'count' => $cities->count(),
            'query' => $query
        ]);
    }

    /**
     * Get user location from IP
     *
     * @return JsonResponse
     */
    public function detectLocation(): JsonResponse
    {
        $locationService = app(\App\Services\LocationService::class);
        $location = $locationService->getLocationFromIp();

        // Store in session
        session([
            'user_location' => $location['city_name'] ?? 'Location',
            'user_country' => $location['country_name'] ?? null,
            'user_city_id' => $location['city']?->id ?? null,
            'user_country_id' => $location['country']?->id ?? null,
        ]);

        return response()->json([
            'success' => true,
            'data' => $location,
            'display_name' => $location['city_name'] ?? 'Location'
        ]);
    }

    /**
     * Update user location manually
     *
     * @return JsonResponse
     */
    public function updateLocation(\Illuminate\Http\Request $request): JsonResponse
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id'
        ]);

        $city = City::with('country')->find($validated['city_id']);

        session([
            'user_location' => $city->name,
            'user_country' => $city->country->name,
            'user_city_id' => $city->id,
            'user_country_id' => $city->country_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => [
                'city' => $city->name,
                'country' => $city->country->name
            ]
        ]);
    }
}

