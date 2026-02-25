<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name'            => $this->first_name,
            'last_name'             => $this->last_name,
            'user_name'             => $this->user_name,
            'phone'                 => $this->phone,
            'about'                 => $this->about,
            'address'               => $this->address,
            'email'                 => $this->email,
            'email_verified_at'     => $this->email_verified_at,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
            'image'                 => MediaResource::collection($this->media),
        ];
    }
}
