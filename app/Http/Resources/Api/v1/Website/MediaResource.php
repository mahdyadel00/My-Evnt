<?php

namespace App\Http\Resources\Api\v1\Website;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'name'          => $this->name,
            'description'   => $this->description,
            'path'          => !is_null($this->path) ? asset("storage/$this->path") : null,
            'type'          => $this->type,
            'order'         => $this->order,
            'is_main'       => $this->is_main,
            'size'          => $this->size,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
