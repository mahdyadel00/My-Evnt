<?php

namespace App\Http\Resources\Api\v1\Website;

use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'name'          => optional($this->data()->where('lang', app()->getLocale())->first())->name,
            'country_id'    => $this->country_id,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            "cities"        => CityResource::collection($this->whenLoaded('cities')),
        ];
    }
}
