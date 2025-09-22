<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
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
