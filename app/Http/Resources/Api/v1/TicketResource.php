<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Request;
use App\Http\Resources\Api\v1\TicketQrResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id'            => $this->id,
            'ticket_type'   => $this->ticket_type,
            'price'         => $this->price,
            'quantity'      => $this->quantity,
            'status'        => $this->status,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
            'qr_code'       => new TicketQrResource($this),

        ];
    }
}
