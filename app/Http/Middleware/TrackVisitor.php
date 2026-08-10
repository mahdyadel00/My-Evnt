<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\VisitorSession;
use App\Services\LocationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
                'user_agent'    => Str::limit((string) $request->userAgent(), 255, ''),
                'device'        => Str::limit((string) ($agent->device() ?: ''), 255, ''),
                'browser'       => Str::limit((string) ($agent->browser() ?: ''), 255, ''),
                'os'            => Str::limit((string) ($agent->platform() ?: ''), 255, ''),
                'country'       => Str::limit((string) ($country ?? ''), 255, ''),
                'city'          => Str::limit((string) ($city ?? ''), 255, ''),
                'referrer'      => Str::limit((string) $request->headers->get('referer'), 255, ''),
                'last_activity' => now(),
            ]
        );

        return $response;
    }
}