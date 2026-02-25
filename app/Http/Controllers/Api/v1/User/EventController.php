<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Models\Event;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\EventResource;
use App\Http\Resources\Api\v1\SuccessResource;

class EventController extends Controller
{
    protected function index()
    {
        $validated = request()->validate([
            'name'                      => 'nullable|string|max:255',
            'category_id'               => 'nullable|exists:event_categories,id',
            'city_id'                   => 'nullable|exists:cities,id',
            'company_id'                => 'nullable|exists:companies,id',
            'is_active'                 => 'nullable|boolean',
            'start_date'                => 'nullable|date',
            'end_date'                  => 'nullable|date',
            'ticket_type'               => 'nullable|string',
            'ticket_price'              => 'nullable|numeric',
            'price_type'                => 'nullable|in:free,paid',
            'type'                      => 'nullable|in:new,upcoming,past,popular',
        ]);

        $query = Event::with(['eventDates', 'tickets'])
            ->when($validated['name'] ?? null, function ($query, $name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->when($validated['category_id'] ?? null, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($validated['city_id'] ?? null, function ($query, $cityId) {
                $query->where('city_id', $cityId);
            })
            ->when($validated['company_id'] ?? null, function ($query, $companyId) {
                $query->where('company_id', $companyId);
            })
            ->when(isset($validated['is_active']), function ($query, $isActive) {
                $query->where('is_active', $isActive);
            })
            ->when($validated['start_date'] ?? null, function ($query, $startDate) {
                $query->whereHas('eventDates', function ($subQuery) use ($startDate) {
                    $subQuery->where('start_date', '>=', $startDate);
                });
            })
            ->when($validated['end_date'] ?? null, function ($query, $endDate) {
                $query->whereHas('eventDates', function ($subQuery) use ($endDate) {
                    $subQuery->where('end_date', '<=', $endDate);
                });
            })
            ->when($validated['ticket_type'] ?? null, function ($query, $ticketType) {
                $query->whereHas('tickets', function ($subQuery) use ($ticketType) {
                    $subQuery->where('type_ticket', $ticketType);
                });
            })
            ->when($validated['ticket_price'] ?? null, function ($query, $ticketPrice) {
                $query->whereHas('tickets', function ($subQuery) use ($ticketPrice) {
                    $subQuery->where('price', $ticketPrice);
                });
            })
            ->when($validated['price_type'] ?? null, function ($query, $priceType) {
                $query->whereHas('tickets', function ($subQuery) use ($priceType) {
                    $subQuery->where('price', $priceType === 'free' ? 0 : '>', 0);
                });
            })
            ->when($validated['type'] ?? null, function ($query, $type) {
                if ($type === 'new') {
                    $query->new();
                } elseif ($type === 'upcoming') {
                    $query->upcoming();
                } elseif ($type === 'past') {
                    $query->past();
                } elseif ($type === 'popular') {
                    $query->top();
                }
            });

        $events = $query->orderBy('created_at', 'desc')->paginate(config('app.pagination'));

        return $events->isNotEmpty() ? EventResource::collection($events) : new ErrorResource('No events found');
    }

    protected function show($id)
    {
        $event = Event::find($id);

        return $event ? new EventResource($event) : new ErrorResource(__('admin.not_found', ['attribute' => __('attributes.event')]));
    }

    public function favorites()
    {
        $userId = auth()->id();

        $events = Event::with('favourites')
            ->whereHas('favourites', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->paginate(config('app.pagination'));

        return $events->isNotEmpty() ? EventResource::collection($events) : response()->json(['message' => 'No favorite events found'], 404);
    }

    protected function favorite($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return new ErrorResource('Event not found');
        }

        $user = auth()->user();

        if ($user->favourites()->where('event_id', $event->id)->exists()) {
            $user->favourites()->where('event_id', $event->id)->delete();
            return SuccessResource::make('Event removed from favorites');
        }
        $user->favourites()->create(['event_id' => $event->id]);
        return SuccessResource::make('Event added to favorites');
    }
}
