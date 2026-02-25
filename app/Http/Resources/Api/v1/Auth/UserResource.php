<?php

namespace App\Http\Resources\Api\v1\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'                    => $this->id,
            'first_name'            => $this->first_name,
            'middle_name'           => $this->middle_name,
            'last_name'             => $this->last_name,
            'user_name'             => $this->user_name,
            'about'                 => $this->about,
            'last_login'            => $this->last_login,
            'login_count'           => $this->login_count,
            'email'                 => $this->email,
            'phone'                 => $this->phone,
            'email_verified_at'     => $this->email_verified_at,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,
            'media'                 => MediaResource::collection($this->media),
            'country'               => new CountryResource($this->country),
        ];
    }
}
