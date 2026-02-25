<?php

namespace App\Http\Resources\Api\v1\Website;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\City;

class SliderResource extends JsonResource
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
            'title'         => $this->title,
            'description'   => $this->description,
            'url'           => $this->url,
            'media'         => MediaResource::collection($this->media),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
