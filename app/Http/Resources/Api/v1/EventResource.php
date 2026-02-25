<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        return [
            'id'                    => $this->id,
            'name'                  => $this->name,
            'description'           => $this->description,
            'poster'                => $this->whenNotNull($this->media ? $this->media->where('name', 'poster')->first() : null),
            'banner'                => $this->whenNotNull($this->media ? $this->media->where('name', 'banner')->first() : null),
            'company'               => new CompanyResource($this->company),
            'category'              => new EventCategoryResource($this->category),
            'city'                  => new CityResource($this->city),
            'currency'              => new CurrencyResource($this->currency),
            'tickets'               => TicketResource::collection($this->tickets),
            'event_dates'           => EventDateResource::collection($this->eventDates ?? []),
            'format'                => $this->format,
            'address'               => $this->address,
            'is_active'             => $this->is_active,
            'is_available'          => $this->eventDates->max('end_date') > now(),
            'cancellation_policy'   => $this->cancellation_policy,
            'location'              => $this->location,
            'organized_by'          => $this->organized_by,
            'upload_by'             => $this->upload_by,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
            'is_favorite'           => $this->favourites()->where('user_id', auth()->id())->exists(),
            'view_count'            => $this->view_count, 
            'external_link'         => $this->external_link,
            'link_meeting'          => $this->link_meeting,
           'duration'               => $this->eventDates->count() > 0
                                        ? $this->eventDates->pluck('start_date')->unique()->count()
                                        : 0,
            'gallery'               => $this->media ? $this->media->where('name', 'images')->values() : [],
        ];
    }
}
