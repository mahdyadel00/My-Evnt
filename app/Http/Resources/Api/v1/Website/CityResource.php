<?php

namespace App\Http\Resources\Api\v1\Website;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\City;

class CityResource extends JsonResource
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
            'name'          => isset($this->TranslatedData) ? $this->TranslatedData->name : $this->data()->first()?->name,
            'lang'          => isset($this->TranslatedData) ? $this->TranslatedData->lang : $this->data()->first()?->lang,
            'state_id'      => $this->state_id,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
