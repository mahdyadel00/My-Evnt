<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VisitorSession;
use App\Services\LocationService;
use Jenssegers\Agent\Agent;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Ignore if the path is Admin or API
        if ($request->is('admin/*') || $request->is('api/*')) {
            return $response;
        }

        $sessionId = session()->getId();
        $ip = $request->ip();

        // Analyze the device and browser
        $agent = new Agent();
        $agent->setUserAgent($request->userAgent());

        // Get the country and city from the IP using LocationService
        $country = null;
        $city = null;
        
        try {
            $locationService = app(LocationService::class);
            $location = $locationService->getLocationFromIp($ip);
            
            $country = $location['country_name'] ?? null;
            $city = $location['city_name'] ?? null;
        } catch (\Exception $e) {
            // Ignore the error
        }

        // Determine the event_id or event_uuid based on the path
        $eventUuid = null;
        if ($request->is('event/*')) {
            $eventUuid = $request->route('eventId')
                ?? $request->route('event')
                ?? $request->route('uuid')
                ?? null;
        }

        // Save or update the record in the database
        VisitorSession::updateOrCreate(
            [
                'session_id' => session()->getId(),
            ],
            [
                'user_id'       => Auth::id(),
                'event_id'      => $request->route('uuid'),
                'ip_address'    => $ip,
                'user_agent'    => $request->userAgent(),
                'device'        => $agent->device(),
                'browser'       => $agent->browser(),
                'os'            => $agent->platform(),
                'country'       => $country,
                'city'          => $city,
                'referrer'      => $request->headers->get('referer'),
                'last_activity' => now(),
            ]
        );

        return $response;
    }
}