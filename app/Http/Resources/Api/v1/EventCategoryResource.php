<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

       return [
            'id'                => $this->id,
            'name'              => $this->name,
            'description'       => $this->description,
            'path'             => asset('storage/' . ($this->media->first()->path ?? 'default.jpg')),
           'is_parent'          => $this->is_parent,
            'parent_id'         => $this->parent_id,
            'child'             => EventCategoryResource::collection($this->child),
           'events'             => $this->whenNotNull($this->events),
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
