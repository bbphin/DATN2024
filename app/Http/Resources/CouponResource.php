<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this?->id,
            'code' => $this?->code,
            'discount_type' => $this?->discount_type,
            'start_date' => $this?->start_date,
            'end_date' => $this?->end_date,
            'min_spend' => $this?->min_spend,
            'max_discount_amount' => $this?->max_discount_amount,
            'total_usage_count' => $this?->total_usage_count,
            'user_usage_count' => $this?->user_usage_count,
        ];
    }
}
