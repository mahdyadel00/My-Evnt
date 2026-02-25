<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\EventResource;

class TicketController extends Controller
{


    public function myTickets(Request $request)
    {
        $user = auth()->user();
        $type = in_array($request->query('type'), ['old', 'new']) ? $request->query('type') : null;
        $today = now()->toDateString();

        $ticketEventIds = $user->userTickets()->pluck('event_id')->unique();

        $eventsQuery = Event::whereIn('id', $ticketEventIds);

        if ($type === 'old') {
            $eventsQuery->whereHas('eventDates', function ($q) use ($today) {
                $q->whereDate('end_date', '<', $today);
            });
        } elseif ($type === 'new') {
            $eventsQuery->whereHas('eventDates', function ($q) use ($today) {
                $q->whereDate('end_date', '>=', $today);
            });
        }

        $eventsQuery = $eventsQuery->with(['eventDates', 'company']);

        $paginatedEvents = $eventsQuery->paginate(config('app.pagination'));

        return $paginatedEvents->count() > 0
            ? EventResource::collection($paginatedEvents)
            : new ErrorResource('No events found');
    }


    public function myTicket($id)
    {
        $user = auth()->user();

        $event = Event::where('id', $id)
            ->whereIn('id', $user->userTickets()->pluck('event_id')->unique())
            ->first();

        if (!$event) {
            return new ErrorResource('No event found');
        }

        return new EventResource($event);
    }
}
