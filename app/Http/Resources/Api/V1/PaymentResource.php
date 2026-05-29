<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'borrower_id' => $this->borrower_id,
            'utang_entry_id' => $this->utang_entry_id,
            'date' => $this->date,
            'amount' => $this->amount,
            'method' => $this->method,
            'notes' => $this->notes,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
