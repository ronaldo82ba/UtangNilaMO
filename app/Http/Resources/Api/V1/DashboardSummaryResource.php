<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardSummaryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'total_utang' => $this['total_utang'],
            'total_payments' => $this['total_payments'],
            'total_balance' => $this['total_balance'],
        ];
    }
}
