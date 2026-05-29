<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UtangEntryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'borrower_id' => $this->borrower_id,
            'date' => $this->date,
            'amount' => $this->amount,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'status' => $this->status,

            'payments' => PaymentResource::collection($this->payments),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
