<?php

namespace App\Http\Resources\Api\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class TicketQrResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'qr_code' => $this->qr_code,
        ];
    }
}
