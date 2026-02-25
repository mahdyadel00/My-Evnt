<?php

namespace App\Http\Controllers\Api\v1\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v1\ErrorResource;
use App\Http\Resources\Api\v1\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    protected function index()
    {
        $events = Event::when(request('name'), function ($query) {
            $query->where('name', 'like', '%' . request('name') . '%');
        })->when(request('category_id'), function ($query) {
            $query->where('category_id', request('category_id'));
        })->when(request('city_id'), function ($query) {
            $query->where('city_id', request('city_id'));
        })->when(request('company_id'), function ($query) {
            $query->where('company_id', request('company_id'));
        })->when(request('is_active'), function ($query) {
            $query->where('is_active', request('is_active'));
        })->when(request('start_date'), function ($query) {
            $query->whereHas('eventDates', function ($query) {
                $query->where('start_date', '>=', request('start_date'));
            });
        })->when(request('end_date'), function ($query) {
            $query->whereHas('eventDates', function ($query) {
                $query->where('end_date', '<=', request('end_date'));
            });
            //search by ticket of type
        })->when(request('ticket_type'), function ($query) {
            $query->whereHas('tickets', function ($query) {
                $query->where('type', request('ticket_type'));
            });
            //search by ticket of price
        })->when(request('ticket_price'), function ($query) {
            $query->whereHas('tickets', function ($query) {
                $query->where('price', request('ticket_price'));
            });

        })->paginate(config("app.pagination"));

        return Count($events) > 0
            ? EventResource::collection($events->load('category', 'city', 'company', 'currency', 'tickets' , 'media'))
            : new ErrorResource('No events found');
    }


    protected function show($id)
    {
        $event = Event::find($id);

        return $event
            ? new EventResource($event)
            : new ErrorResource(__('admin.not_found', ['attribute' => __('attributes.event')]));
    }
}
