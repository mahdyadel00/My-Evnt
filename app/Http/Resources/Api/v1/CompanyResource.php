<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id'                            => $this->id,
            'first_name'                    => $this->first_name,
            'last_name'                     => $this->last_name,
            'user_name'                     => $this->user_name,
            'company_name'                  => $this->company_name,
            'email'                         => $this->email,
            'phone'                         => $this->phone,
            'website'                       => $this->website,
            'description'                   => $this->description,
            'address'                       => $this->address,
            'commercial_register_image'     => MediaResource::collection($this->media->where('name', 'commercial_register_image')),
            'tax_card'                      => MediaResource::collection($this->media->where('name', 'tax_card')),
            'commercial_record'             => MediaResource::collection($this->media->where('name', 'commercial_record')),
            'image'                         => MediaResource::collection($this->media->where('name', 'image')),
        ];
    }
}
