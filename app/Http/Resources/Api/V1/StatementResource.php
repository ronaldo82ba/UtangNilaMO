<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class StatementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'borrower' => new BorrowerResource($this['borrower']),
            'utang' => UtangEntryResource::collection($this['borrower']->utangEntries),
            'payments' => PaymentResource::collection($this['borrower']->payments),
            'balance' => $this['borrower']->utangEntries->sum('amount') - $this['borrower']->payments->sum('amount'),
            'generated_at' => now()->toDateTimeString(),
        ];
    }
}
