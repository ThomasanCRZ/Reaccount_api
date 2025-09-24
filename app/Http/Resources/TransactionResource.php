<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'       => $this->id,
            'service'  => $this->service,
            'amount'   => $this->amount,
            'type'     => $this->type,
            'category' => $this->category,
            'date'     => $this->date,
        ];
    }
}
