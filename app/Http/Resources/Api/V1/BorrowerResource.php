<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class BorrowerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nickname' => $this->nickname,
            'phone' => $this->phone ?? $this->contact_number,
            'notes' => $this->notes,

            'total_utang' => $this->utangEntries->sum('amount'),
            'total_payments' => $this->payments->sum('amount'),
            'balance' => $this->utangEntries->sum('amount') - $this->payments->sum('amount'),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'links' => [
                'utang' => route('api.borrowers.utang.index', $this->id),
                'payments' => route('api.borrowers.payments.index', $this->id),
                'statement' => route('api.borrowers.statement', $this->id),
            ],
        ];
    }
}
